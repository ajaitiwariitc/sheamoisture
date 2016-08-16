<?php
ini_set('date.timezone', 'Europe/Amsterdam');
date_default_timezone_set('Europe/Amsterdam');

class ImageAttributeItemProcessor extends Magmi_ItemProcessor
{

	protected $forcename=null;
	protected $magdir=null;
	protected $imgsourcedirs=array();
	protected $errattrs=array();
	protected $_lastnotfound="";
	protected $_lastimage="";
    protected $_handled_attributes=array();
	protected $_img_baseattrs=array("image","small_image","thumbnail");
	protected $_active=false;
	protected $_newitem;
	protected $_mdh;
	protected $debug;
	
	public function initialize($params)
	{
		//declare current class as attribute handler
		$this->registerAttributeHandler($this,array("frontend_input:(media_image|gallery)"));
		$this->magdir=Magmi_Config::getInstance()->getMagentoDir();
		$this->_mdh=MagentoDirHandlerFactory::getInstance()->getHandler($this->magdir);
		
		$this->forcename=$this->getParam("IMG:renaming");
		foreach($params as $k=>$v)
		{
			if(preg_match_all("/^IMG_ERR:(.*)$/",$k,$m))
			{
				$this->errattrs[$m[1][0]]=$params[$k];
			}
		}
		$this->debug=$this->getParam("IMG:debug",0);
	}

	public function getPluginInfo()
	{
		return array(
            "name" => "Image attributes processor",
            "author" => "Dweeves",
            "version" => "1.0.25",
			"url"=>$this->pluginDocUrl("Image_attributes_processor")
            );
	}
	
	public function handleGalleryTypeAttribute($pid,&$item,$storeid,$attrcode,$attrdesc,$ivalue)
	{
		//do nothing if empty
		if($ivalue=="")
		{
			return false;
		}
		//use ";" as image separator
		$images=explode(";",$ivalue);
		$imageindex=0;
		//for each image
		
		
		
		
		
		
		foreach($images as $imagefile)
		{
			//trim image file in case of spaced split
			$imagefile=trim($imagefile);
			//handle exclude flag explicitely
			$exclude=$this->getExclude($imagefile,false); 
			$infolist=explode("::",$imagefile);
			$label=null;
			if(count($infolist)>1)
			{
				$label=$infolist[1];
				$imagefile=$infolist[0];
			}
			
			unset($infolist);
			//copy it from source dir to product media dir
			$imagefile=$this->copyImageFile($imagefile,$item,array("store"=>$storeid,"attr_code"=>$attrcode,"imageindex"=>$imageindex==0?"":$imageindex));
			if($imagefile!==false)
			{
				//add to gallery
				//print_r($imagefile);exit;
				$targetsids=$this->getStoreIdsForStoreScope($item["store"]);
				$vid=$this->addImageToGallery($pid,$storeid,$attrdesc,$imagefile,$targetsids,$label,$exclude);
				//----
				if(isset($item["thumbnail"]) && $item["image"] && $item["thumbnail"] != "" && $item["image"] != "") {
					$vXid=$this->addImageToVarchar($pid,$storeid,$attrdesc,$imagefile,$targetsids,$label,$exclude,$attrdesc["attribute_id"],$item);
				}
				
			}
			$imageindex++;
		}
		unset($images);
		//we don't want to insert after that
		$ovalue=false;
		return $ovalue;
	}

	public function removeImageFromGallery($pid,$storeid,$attrdesc)
	{
		$t=$this->tablename('catalog_product_entity_media_gallery');
		$tv=$this->tablename('catalog_product_entity_media_gallery_value');
		$tvte=$this->tablename('catalog_product_entity_media_gallery_value_to_entity');
		
		//echo "$pid,$storeid,".$attrdesc["attribute_id"];
		
		//print_r($attrdesc);
		$sql="DELETE $tv.* FROM $tv 
			  JOIN $t ON $t.value_id=$tv.value_id AND $t.attribute_id=?
			  JOIN $tvte ON $tvte.value_id=$t.value_id AND $tvte.entity_id=?
			  WHERE  $tv.store_id=?";
		
		/*
		
			$sql="DELETE $tv.* FROM $tv 
			  JOIN $t ON $t.value_id=$tv.value_id AND $t.entity_id=? AND $t.attribute_id=?
			  WHERE  $tv.store_id=?";
			  
			  
			DELETE catalog_product_entity_media_gallery_value.* FROM catalog_product_entity_media_gallery_value
			JOIN catalog_product_entity_media_gallery ON catalog_product_entity_media_gallery.value_id=catalog_product_entity_media_gallery_value.value_id
			AND 
			catalog_product_entity_media_gallery.attribute_id=84
			JOIN catalog_product_entity_media_gallery_value_to_entity 
			ON catalog_product_entity_media_gallery.value_id=catalog_product_entity_media_gallery_value_to_entity.value_id  AND catalog_product_entity_media_gallery_value_to_entity.entity_id= 2059
			WHERE  
			catalog_product_entity_media_gallery_value.store_id=0


			
				
		*/	
		
		$this->delete($sql,array($attrdesc["attribute_id"],$pid,$storeid));
		
	}
	
	public function getExclude(&$val,$default=true)
	{
		$exclude=$default;
		if($val[0]=="+" || $val[0]=="-")
		{
			$exclude=$val[0]=="-";
			$val=substr($val,1);
		}
		return $exclude;
	}
	
	public function findImageFile($ivalue)
	{
		//do no try to find remote image
		if(is_remote_path($ivalue))
		{
			return $ivalue;
		}
		//if existing, return it directly
		if(realpath($ivalue))
		{
			return $ivalue;
		}

		//ok , so it's a relative path
		$imgfile=false;
		$scandirs=explode(";",$this->getParam("IMG:sourcedir"));
		
		//iterate on image sourcedirs, trying to resolve file name based on input value and current source dir
		for($i=0;$i<count($scandirs) && $imgfile===false;$i++)
		{
			$sd=$scandirs[$i];
			//scandir is relative, use mdh
			if($sd[0]!="/")
			{
				$sd=$this->_mdh->getMagentoDir()."/".$sd;
			}
			$imgfile=abspath($ivalue,$sd);
		}
		
		return $imgfile;
	}
	
	public function handleImageTypeAttribute($pid,&$item,$storeid,$attrcode,$attrdesc,$ivalue)
	{
		//$attrdesc,$item
		//echo $ivalue."$pid,$storeid,$attrcode,$ivalue,$attrdesc"
		//print_r($attrdesc);
		//echo '<pre>';echo '<br>-------------------------Ajai---------------------<br>';
		//exit;
		$ivalue="";
		//remove attribute value if empty
		if($ivalue=="")
		{	//echo 12;
			$this->removeImageFromGallery($pid,$storeid,$attrdesc);
			return "__MAGMI_DELETE__";
		}
		
		//add support for explicit exclude
		$exclude=$this->getExclude($ivalue,true); 
		
		//else copy image file
		$imagefile=$this->copyImageFile($ivalue,$item,array("store"=>$storeid,"attr_code"=>$attrcode));
		$ovalue=$imagefile;
		//add to gallery as excluded
		if($imagefile!==false)
		{
			$label=null;
			if(isset($item[$attrcode."_label"]))
			{
				$label=$item[$attrcode."_label"];
			}
			$targetsids=$this->getStoreIdsForStoreScope($item["store"]);
			$vid=$this->addImageToGallery($pid,$storeid,$attrdesc,$imagefile,$targetsids,$label,$exclude,$attrdesc["attribute_id"]);
			//
		}
		return $ovalue;
	}


	public function handleVarcharAttribute($pid,&$item,$storeid,$attrcode,$attrdesc,$ivalue)
	{
		//if it's a gallery
		
		switch($attrdesc["frontend_input"])
		{
			case "gallery":
				$ovalue=$this->handleGalleryTypeAttribute($pid,$item,$storeid,$attrcode,$attrdesc,$ivalue);
				break;
			case "media_image":
				$ovalue=$this->handleImageTypeAttribute($pid,$item,$storeid,$attrcode,$attrdesc,$ivalue);
				break;
			default:
				$ovalue="__MAGMI_UNHANDLED__";
		}
		return $ovalue;
	}

	/**
	 * imageInGallery
	 * @param int $pid  : product id to test image existence in gallery
	 * @param string $imgname : image file name (relative to /products/media in magento dir)
	 * @return bool : if image is already present in gallery for a given product id
	 */
	public function getImageId($pid,$attid,$imgname,$refid=null)
	{
		
		$t=$this->tablename('catalog_product_entity_media_gallery');
		$mgvte = $this->tablename('catalog_product_entity_media_gallery_value_to_entity');
		$sql="SELECT $t.value_id FROM $t JOIN $mgvte ON $t.value_id = $mgvte.value_id ";
		if($refid!=null)
		{	
			$vc = $this->tablename('catalog_product_entity_varchar');
			//$sql.=" JOIN $mgvte ON $t.value_id = $mgvte.value_id ";
			$sql.=" JOIN $vc ON $mgvte.entity_id = $vc.entity_id AND $vc.attribute_id=?
					WHERE $mgvte.entity_id=?";
			/*
			$sql.=" JOIN $vc ON $t.entity_id=$vc.entity_id AND $t.value=$vc.value AND $vc.attribute_id=?
					WHERE $t.entity_id=?";		
			*/		
					/*
					Plugin need to modify
- Related, Upsell , crossSell
- Configurable Products
- Bundle Products
- Grouped products
					SELECT catalog_product_entity_media_gallery.value_id FROM catalog_product_entity_media_gallery  
						
						JOIN catalog_product_entity_media_gallery_value_to_entity
							ON catalog_product_entity_media_gallery_value_to_entity.value_id=catalog_product_entity_media_gallery.value_id
							
						JOIN catalog_product_entity_varchar
							ON catalog_product_entity_media_gallery_value_to_entity.entity_id = catalog_product_entity_varchar.entity_id
							AND catalog_product_entity_varchar.attribute_id=84
					WHERE 
							catalog_product_entity_media_gallery_value_to_entity.entity_id=2059		
					
					
					*/
			$imgid=$this->selectone($sql,array($refid,$pid),'value_id');
		}
		else
		{	
			//echo "$imgname,$pid,$attid";
			//echo '<br>';
			$sql.=" WHERE value=? AND entity_id=? AND attribute_id=?";
			//echo "Query:".$sql;
			/*
			SELECT catalog_product_entity_media_gallery.value_id  
			FROM catalog_product_entity_media_gallery  
			JOIN catalog_product_entity_media_gallery_value_to_entity
			ON catalog_product_entity_media_gallery_value_to_entity.value_id=catalog_product_entity_media_gallery.value_id
			WHERE value='/b/a/base.d-22222222.jpg' AND entity_id=2059 AND attribute_id=87
			
			
			JOIN catalog_product_entity_media_gallery_value_to_entity
							ON catalog_product_entity_media_gallery_value_to_entity.value_id=catalog_product_entity_media_gallery.value_id
			*/				
			$imgid=$this->selectone($sql,array($imgname,$pid,$attid),'value_id');
		}
		
		if($imgid==null)
		{
			// insert image in media_gallery
			$sql="INSERT INTO $t
				(attribute_id,value)
				VALUES
				(?,?)";
			$imgid = $this->insert($sql,array($attid,$imgname));
			$sqlMgvte = "INSERT INTO catalog_product_entity_media_gallery_value_to_entity
				(value_id,entity_id)
				 VALUES(?,?)";
			$imgMgvteid = $this->insert($sqlMgvte,array($imgid,$pid));	
		}
		else
		{
			$sql="UPDATE $t
				 SET value=?
				 WHERE value_id=?";
			$this->update($sql,array($imgname,$imgid));
		}
		return $imgid;
	}

	/**
	 * reset product gallery
	 * @param int $pid : product id
	 */
	public function resetGallery($pid,$storeid,$attid)
	{
		$tgvte=$this->tablename('catalog_product_entity_media_gallery_value_to_entity');
		$tgv=$this->tablename('catalog_product_entity_media_gallery_value');
		$tg=$this->tablename('catalog_product_entity_media_gallery');
		
		$sql="DELETE emgv,emg,emgvte FROM `$tgv` as emgv 
			JOIN `$tg` AS emg ON emgv.value_id = emg.value_id AND emgv.store_id=?
			JOIN `$tgvte` AS emgvte ON emgvte.value_id = emg.value_id
			WHERE emgvte.entity_id=? AND emg.attribute_id=?";
		//$sql;
		$this->delete($sql,array($storeid,$pid,$attid));

	}
	
	public function addImageToVarchar($pid,$storeid,$attrdesc,$imgname,$targetsids,$imglabel=null,$excluded=false,$refid=null,$item)
	{
		/* XXXXXXXXXX-------------------Saturday-6-August-2016--------------------XXXX  NULL, '84', '0', '2273', '/7/6/764302290339.png */
			$imgInserts=array();
			$imgData=array();
			
			$imageDatabase = $imgname;
			$imageEid = $pid;
			$imageSid = $storeid;
			foreach($this->_img_baseattrs as $attrcode)
			{
				$inf =$this->getAttrInfo($attrcode);
				if(count($inf)>0)
				{
					$imageAid = $inf["attribute_id"];
					$attids[] = $inf["attribute_id"];
					$imgInserts[]="(?,?,?,?)";
					$imgData = array_merge($imgData,array($imageAid,$imageSid,$imageEid,$imageDatabase));
				}
			}
			$sql_image_for_product_varchar = "INSERT IGNORE INTO `catalog_product_entity_varchar` (`attribute_id`, `store_id`, `entity_id`, `value`) VALUES ".implode(",",$imgInserts)."";
			$this->insert($sql_image_for_product_varchar, $imgData);
			// --- Saturday-6-August-2016
	}	
	
	/**
	 * adds an image to product image gallery only if not already exists
	 * @param int $pid  : product id to test image existence in gallery
	 * @param array $attrdesc : product attribute description
	 * @param string $imgname : image file name (relative to /products/media in magento dir)
	 */
	public function addImageToGallery($pid,$storeid,$attrdesc,$imgname,$targetsids,$imglabel=null,$excluded=false,$refid=null)
	{
		$gal_attinfo=$this->getAttrInfo("media_gallery");
		$tg=$this->tablename('catalog_product_entity_media_gallery');
		$tgv=$this->tablename('catalog_product_entity_media_gallery_value');
		$tgvte=$this->tablename('catalog_product_entity_media_gallery_value_to_entity');
		
		$vid = $this->getImageId($pid,$gal_attinfo["attribute_id"],$imgname,$refid);
		if($vid!=null)
		{
			#get maximum current position in the product gallery
			$sql=" SELECT MAX( position ) as maxpos
					 FROM $tgv AS emgv
					 JOIN $tg AS emg ON emg.value_id = emgv.value_id
					 JOIN $tgvte AS emgvte ON emgvte.value_id = emg.value_id AND emgvte.entity_id = ?
					 WHERE emgv.store_id=?
			 		 GROUP BY emgvte.entity_id";
			
			/*
			$sql="SELECT MAX( position ) as maxpos
					 FROM $tgv AS emgv
					 JOIN $tg AS emg ON emg.value_id = emgv.value_id AND emg.entity_id = ?
					 WHERE emgv.store_id=?
			 		 GROUP BY emg.entity_id";
			*/		 
				 
			$pos=$this->selectone($sql,array($pid,$storeid),'maxpos');
			$pos=($pos==null?0:$pos+1);
			#insert new value (ingnore duplicates)
				
			$vinserts=array();
			$data=array();
			 
			foreach($targetsids as $tsid)
			{
				$vinserts[]="(?,?,?,?,".($imglabel==null?"NULL":"?").",?)";
				$data=array_merge($data,array($vid,$tsid,$pos,$excluded?1:0,$pid));
				if($imglabel!=null)
				{
					$data[]=$imglabel;
				}
			}
			
			if(count($data)>0)
			{
				$sql="INSERT INTO $tgv
					(value_id,store_id,position,disabled,label,entity_id)
					VALUES ".implode(",",$vinserts)." 
					ON DUPLICATE KEY UPDATE label=VALUES(`label`)";
					
					/*Ajai
					INSERT INTO catalog_product_entity_media_gallery_value
					(value_id,store_id,position,disabled,label,entity_id)
					VALUES (3441,0,3,0,'Image',2059) 
					ON DUPLICATE KEY UPDATE label='Image'
					*/
				$this->insert($sql,$data);
			}
			//exit;
			unset($vinserts);
			unset($data);
		}
	}
	
	public function parsename($info,$item,$extra)
	{
		$info=$this->parseCalculatedValue($info,$item,$extra);
		return $info;
	}
	
	public function getPluginParams($params)
	{
		$pp=array();
		foreach($params as $k=>$v)
		{
			if(preg_match("/^IMG(_ERR)?:.*$/",$k))
			{
				$pp[$k]=$v;
			}
		}
		return $pp;
	}
	
	public function fillErrorAttributes(&$item)
	{
		foreach($this->errattrs as $k=>$v)
		{
			$this->addExtraAttribute($k);
			$item[$k]=$v;
		}
	}
	
	public function getImagenameComponents($fname,$formula,$extra)
	{
		$matches=array();
		$xname=$fname;
		if(preg_match("|re::(.*)::(.*)|",$formula,$matches))
		{
			$rep=$matches[2];
			$xname=preg_replace("|".$matches[1]."|",$rep,$xname);
			$extra['parsed']=true;
		}
		$xname=basename($xname);
		$m=preg_match("/(.*)\.(jpg|png|gif)$/i",$xname,$matches);
		if($m)
		{
			$extra["imagename"]=$xname;
			$extra["imagename.ext"]=$matches[2];
			$extra["imagename.noext"]=$matches[1];
		}
		else
		{
			$uid=uniqid("img",true);
			$extra=array_merge($extra,array("imagename"=>"$uid.jpg","imagename.ext"=>"jpg","imagename.noext"=>$uid));
		}
			
		return $extra;
	}
	
	public function getTargetName($fname,$item,$extra)
	{
		$cname=basename($fname);
		if(isset($this->forcename) && $this->forcename!="")
		{
			$extra=$this->getImagenameComponents($fname,$this->forcename,$extra);
			$pname=($extra['parsed']?$extra['imagename']:$this->forcename);
			$cname=$this->parsename($pname,$item,$extra);
		}
		$cname=strtolower(preg_replace("/%[0-9][0-9|A-F]/","_",rawurlencode($cname)));
		
		return $cname;
	}
	
	public function saveImage($imgfile,$target)
	{
		/*echo "<br>";
		echo "$imgfile";
		echo "<br>".$target;
		echo "<br>";
		echo $imgfile; */
		$imgfile = str_replace(';','',$imgfile);
		$result=$this->_mdh->copy($imgfile,$target);
		return true;		
	}
	
	
	/**
	 * copy image file from source directory to
	 * product media directory
	 * @param $imgfile : name of image file name in source directory
	 * @return : name of image file name relative to magento catalog media dir,including leading
	 * directories made of first char & second char of image file name.
	 */
	public function copyImageFile($imgfile,&$item,$extra)
	{
		
		if($imgfile==$this->_lastnotfound)
		{
			if($this->_newitem){
				$this->fillErrorAttributes($item);
			};
			return false;
		}
		
		$source=$this->findImageFile($imgfile);
		
		if($source==false)
		{
			$this->log("$imgfile cannot be found in images path","warning");
			return false;
		}
		$imgfile=$source;
		$checkexist= ($this->getParam("IMG:existingonly")=="yes");
		$curlh=false;
		$bimgfile=$this->getTargetName($imgfile,$item,$extra);
		$bimgfile = str_replace('.jpg_','.jpg',$bimgfile);
		//source file exists
		$i1=$bimgfile[0];
		$i2=$bimgfile[1];
		$l2d="pub/media/catalog/product/$i1/$i2";
		$te="$l2d/$bimgfile";
		$result="/$i1/$i2/$bimgfile";
		/**/
		echo "<br> imgfile:".$imgfile;
		echo "<br>";
		echo "<br> result:".$result;
		echo "<br>";
		print_r($extra);
		
		
		/* test for same image */
		if($result==$this->_lastimage)
		{
			return $result;
		}
		/* test if imagefile comes from export */
		if(!$this->_mdh->file_exists("$te") || $this->getParam("IMG:writemode")=="override")
		{	//echo "If Started";
			/* try to recursively create target dir */
			if(!$this->_mdh->file_exists("$l2d"))
			{
				//echo "file_exists Started";
				$tst=$this->_mdh->mkdir($l2d,Magmi_Config::getInstance()->getDirMask(),true);
				if(!$tst)
				{
					$errors=$this->_mdh->getLastError();
					$this->log("error creating $l2d: {$errors["type"]},{$errors["message"]}","warning");
					unset($errors);
					return false;
				}
			}
			//$imgfile = "C:/xampp/htdocs/magento/var/import/media/import/wsh12-purple_main.jpg";
			//echo "<br> imgfile Source :".$imgfile;
			//echo "<br> imgfile:"."$l2d/$bimgfile";
			//exit;
			if(!$this->saveImage($imgfile,"$l2d/$bimgfile"))
			{	echo "saveImage Started";
				$errors=$this->_mdh->getLastError();
				$this->fillErrorAttributes($item);
				$this->log("error copying $l2d/$bimgfile : {$errors["type"]},{$errors["message"]}","warning");
				unset($errors);
				return false;
			}
			else
			{	//echo " else saveImage Started";
				@$this->_mdh->chmod("$l2d/$bimgfile",Magmi_Config::getInstance()->getFileMask());			
			}
		}
		//echo "<br>";
		//echo 123; exit;
		$this->_lastimage=$result;
		/* return image file name relative to media dir (with leading / ) */
		return $result;
	}
	
	public function updateLabel($attrdesc,$pid,$sids,$label)
	{
		$tg=$this->tablename('catalog_product_entity_media_gallery');
		$tgv=$this->tablename('catalog_product_entity_media_gallery_value');
		$vc=$this->tablename('catalog_product_entity_varchar');
		$sql="UPDATE $tgv as emgv 
		JOIN $tg as emg ON emg.value_id=emgv.value_id AND emg.entity_id=?
		JOIN $vc  as ev ON ev.entity_id=emg.entity_id AND ev.value=emg.value and ev.attribute_id=? 
		SET label=? 
		WHERE emgv.store_id IN (".implode(",",$sids).")";
		$this->update($sql,array($pid,$attrdesc["attribute_id"],$label));
	}
	
	public function processItemAfterId(&$item,$params=null)
	{
		if(!$this->_active)
		{
			return true;
		}
		$this->_newitem=$params["new"];
		$pid=$params["product_id"];
		///echo "<br>========XXXX====<br>";
		//print_r($item);
		//echo "<br>========XXXX====<br>";
		foreach($this->_img_baseattrs as $attrcode)
		{
			//if only image/small_image/thumbnail label is present (ie: no image field)
			if(isset($item[$attrcode."_label"]) && !isset($item[$attrcode]))
			{
				//force label update
				$attrdesc=$this->getAttrInfo($attrcode);
				$this->updateLabel($attrdesc,$pid,$this->getItemStoreIds($item,$attr_desc["is_global"]),$item[$attrcode."_label"]);
				unset($attrdesc);
			}
		}
		
		//Reset media_gallery
		$galreset= !(isset($item["media_gallery_reset"])) || $item["media_gallery_reset"]==1;
		$forcereset = (isset($item["media_gallery_reset"])) && $item["media_gallery_reset"]==1;
		
		if( (isset($item["media_gallery"]) && $galreset) || $forcereset)
		{
			$gattrdesc=$this->getAttrInfo("media_gallery");
			$sids=$this->getItemStoreIds($item,$gattrdesc["is_global"]);
			
			foreach($sids as $sid)
			{
				$this->resetGallery($pid,$sid,$gattrdesc["attribute_id"]);
			}
		}
		return true;
	}
	
	public function processColumnList(&$cols,$params=null)
	{	
		//automatically add modified attributes if not found in datasource
		
		
		//automatically add media_gallery for attributes to handle		
		$imgattrs=array_intersect(array_merge($this->_img_baseattrs,array('media_gallery')),$cols);
		if(count($imgattrs)>0)
		{
			$this->_active=true;
			$cols=array_unique(array_merge(array_keys($this->errattrs),$cols,$imgattrs));
		}
		else
		{
			$this->log("no image attributes found in datasource, disabling image processor","startup");
		}
		return true;
	}
	
	//Cleanup gallery from removed images if no more image values are present in any store 
	public function endImport()
	{	
		if(!$this->_active)
		{
			return;
		}
		$attids=array();
		foreach($this->_img_baseattrs as $attrcode)
		{
			$inf=$this->getAttrInfo($attrcode);
			//print_r($inf);
			
			
			if(count($inf)>0)
			{
				$attids[]=$inf["attribute_id"];
			}
		}
		
		//echo "<br>==============================sssssssss===========<br>";
		
		//INSERT INTO `magento`.`catalog_product_entity_varchar` (`value_id`, `attribute_id`, `store_id`, `entity_id`, `value`) VALUES (NULL, '84', '0', '2273', 'xx.jpg');
		
		//INSERT INTO catalog_product_entity_varchar (value_id, attribute_id, store_id, entity_id, value) VALUES (NULL, '84', '0', '2273', '/7/6/764302290339.png');


		
		//print_r($attids);
		
		if(count($attids)>0)
		{
			$tg=$this->tablename('catalog_product_entity_media_gallery');
			$tgv=$this->tablename('catalog_product_entity_media_gallery_value');
			
			$sql="DELETE emg.* FROM $tg as emg
			LEFT JOIN (SELECT emg.value_id,count(emgv.value_id) as cnt FROM  $tgv as emgv JOIN $tg as emg  ON emg.value_id=emgv.value_id GROUP BY emg.value_id ) as t1 ON t1.value_id=emg.value_id
			WHERE attribute_id IN (".implode(",",$attids).") AND t1.cnt IS NULL";
			$this->delete($sql);
		}
		else
		{
			$this->log("Unexpected problem in image attributes retrieval","warning");
		}	
		unset($attids);
	}
	
}





