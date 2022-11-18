<?php

namespace App\Services\API;

class ItemService {

    public function convertImgToBase64($image_name)
    {
        $path = asset('storage/gambar-item/'.$image_name);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    	return $base64;
    }

   

}