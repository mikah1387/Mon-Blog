<?php 

namespace App\service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService

 {
  private $params;

  public function __construct(ParameterBagInterface $params)
  {
     $this->params = $params;
  }

  public function add(UploadedFile $picture, ?string $folder = '',?int $width =250,?int $height =250 )
  {
     //on donne un nouveau nom a l'image 
      $fichier = md5(uniqid(rand(),true)) . '.webp';
    //   on recupere les infos de l'image 
      $picture_info = getimagesize($picture);
  
      if (! $picture_info){
           
         throw new Exception('format d\'image incorrect');
      }
       //on vérifie le format de l'image 
        switch($picture_info['mime']){

               case 'image/png':
                  $picture_source =  imagecreatefrompng($picture);
               break;
               case 'image/jpeg':
                   $picture_source =  imagecreatefromjpeg($picture);
                break;
                case 'image/webp':
                    $picture_source =  imagecreatefromwebp($picture);
                    break;
                    default:
                  throw new Exception('format d\'image incorrect');

        }
        //   on recadre l'image
        $imageWidth = $picture_info[0];
        $imageHeight = $picture_info[1];
        // on vérifie l'orientation de l'image

        switch ($imageWidth <=> $imageHeight){
            case -1: //portrait
                $squareSize = $imageWidth;
                $src_x = 0 ;
                $src_y = ($imageHeight - $squareSize)/ 2;
                break;
            case 0: //carré
                    $squareSize = $imageWidth;
                    $src_x = 0 ;
                    $src_y = 0;
                    break;
            case 1: //paysage
                        $squareSize = $imageHeight;
                        $src_x = ($imageHeight - $squareSize)/ 2 ;
                        $src_y = 0;
                        break;

        }

        // on crée une nouvelle image vierge 
         $resized_picture = imagecreatetruecolor($width,$height);
         imagecopyresampled($resized_picture, $picture_source, 
          0,0,$src_x,$src_y,$width,$height,$squareSize,$squareSize);

         $path = $this->params->get('images_directory').$folder;
        // on cree dossier distination sil n'existe pas 
         if (!file_exists($path.'/min/')){
            mkdir($path.'/min/',0755, true);
        }
        // on stocke limage recadrée
        imagewebp($resized_picture,$path.'/min/'. $width .'x'. $height . '-'.$fichier);
        $picture->move($path . '/',$fichier);
        return $fichier;

  }


}