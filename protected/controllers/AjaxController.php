<?php 
class AjaxController extends Controller
{
    public function actionIndex()
    {
        return null;
    }
    
    public function actionAnnounceType()
    {
        $id = $_POST['ID'];
        echo AnnounceTypegroup::allOneCat($id);
        //echo '<option>'.$id.'</option>';
    }
    
    
    public function filters()
    {
        return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
    }
    
    public function accessRules()
	{
		return array(
			/*array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update'),
				'roles'=>array('updateAnnounce'=>array(
                        'id'=>$this->model->author_id)),
			),*/
            
            
            array('allow',  // deny all users
                'users'=>array('*'),
  			),
 
		);
	}
    
    public function actionImageGetJson() 
    { 
        $dir = dirname(__FILE__).'/../../images/uploads/thumbs/'; 
        $files = array(); 
        if (is_dir($dir)) 
        { 
            if ($dh = opendir($dir)) 
            { 
                while (($file = readdir($dh)) !== false) 
                { 
                    if ($file != '.' && $file != '..') 
                    $files[] = array( 'thumb' => Yii::app()->request->hostInfo.'/images/uploads/thumbs/'.$file, 
                                        'image' => Yii::app()->request->hostInfo.'/images/uploads/'.$file, 
                                        'title' => $file, 
                                        ); 
                } 
                closedir($dh); 
            } 
        } 
        echo json_encode($files); 
     }
     public function actionFileUpload() 
     { 
        $file=CUploadedFile::getInstanceByName('file'); 
        $filename = md5(time()).'.'.$file->extensionName; 
        $path = dirname(__FILE__).'/../../images/uploads/'.$filename; 
        copy($_FILES['file']['tmp_name'], $path); 
        $array = array( 'filelink' => Yii::app()->request->hostInfo.'/images/uploads/'.$filename, 
                        'filename' => $filename ); 
        echo stripslashes(json_encode($array)); 
     }
     public function actionImageUpload() 
     { 
        //var_dump($_POST);
        //var_dump($_FILES);
        $image=CUploadedFile::getInstanceByName('file'); 
        
        $filename = 'a_'.date('YmdHis').'_'.substr(md5(time()), 0, rand(7, 13)).'.'.$image->extensionName; 
        
        $path = dirname(__FILE__).'/../../images/uploads/'.$filename; 
        //var_dump($path);
        $image->saveAs(dirname(__FILE__).'/../../images/uploads/'.$filename); 
        
        $thumb=Yii::app()->phpThumb->create(dirname(__FILE__).'/../../images/uploads/'.$filename);
        if(isset($thumb))
        {
            $thumb->resize(225,150);
            $thumb->save(dirname(__FILE__).'/../../images/uploads/thumbs/'.$filename);
            
        }
        
        /*$image_open = Yii::app()->image->load(dirname(__FILE__).'/../../images/uploads/'.$filename);
        
        if (isset($image_open)) 
        { 
            if ($image_open->width > $image_open->height) 
                $dim = Image::HEIGHT; else $dim = Image::WIDTH; 
            $image_open->resize(100, 100, $dim)->crop(100, 100); 
            $image_open->save(dirname(__FILE__).'/../../images/uploads/thumbs/'.$filename); 
        } */
        $array = array( 'filelink' => Yii::app()->request->hostInfo.'/images/uploads/'.$filename, 'filename' => $filename ); 
        echo stripslashes(json_encode($array)); 
     }
     
     function actionCheckUsername()
     {
        $id = $_POST['ID'];
        $username = $_POST['Username'];
        $model= User::findAll('username=:username', array(':username'=>$username));
        
        if(count($model)>0)
        {
            echo false;
        }else
        echo true;
        
        
     }
     
    





}
?>