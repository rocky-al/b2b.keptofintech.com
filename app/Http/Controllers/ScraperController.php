<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Models\CommonModel;
use App\Models\CategoryModel;
use App\Models\BrandModel;
use App\Models\BrandModelNumber;
use App\Models\BrandCategoryModel;
use MongoDB\BSON\ObjectId;

class ScraperController extends Controller
{
    public function index()
    {
        $client = new Client();
        
        $website = $client->request('GET', 'https://www.samsung.com/in');
        
        // return $website->html();
        
        $web_data = array(
            'website' => $website,
            'client' => $client  
        );
        $cat_data = $website->filter('.nv00-gnb__l0-menu')->each(function ($node) use (&$web_data) {
            
            $data = array(
                'cat_name' => array(),
                'model' => array(),
            );
            $cat = $node->children('button')->each(function ($child) {
                    // $child->nodeName() for getting nodes like div button
                    if($child->text() != '') return $child->text();
            });

            $model = $node->children('.nv00-gnb__l1-menu-container > .nv00-gnb__l1-menu-wrap > ul > li > a')->each(function ($model_child) use (&$web_data) {
                // CommonModel::pr($web_data,1);
                $model_url = $model_child->attr('href');
                if (filter_var($model_url, FILTER_VALIDATE_URL) === FALSE) {
                    $model_url = 'https://www.samsung.com'.$model_child->attr('href');
                }
                
                // if(!empty($model_child->text())) return $model_url;
                $model_client = new Client();
        
                $model_website = $model_client->request('GET', $model_url);

                $models = $model_website->filter('.nv14-visual-lnb__featured-item-wrap > ul > li > a')->each(function ($model_node) {
                    
                    // return $model_node->text();
                    $model_name = $model_node->children('.nv14-visual-lnb__featured-item-link-text-wrap > p > strong')->each(function ($model_child_name_node)  {
                        return $model_child_name_node->text();
                    });

                    $model_image = $model_node->children('.image > img')->each(function ($model_image_node)  {
                        $model_img = $model_image_node->attr('data-desktop-src');

                        $image_array = array(
                            'img' => $model_img,
                            'file_name' => basename($model_img)
                        );
                        return $image_array;
                    });

                    return [
                        'model_name' => implode("",$model_name),
                        'model_image' => $model_image
                    ];

                });
                return $models;
            });

            $data = array(
                'cat_name' => implode("",$cat),
                'brand' => 'Samsung',
                'models' => $model
            );

            return $data;
        });
        // echo json_encode($cat_data);
        CommonModel::pr($cat_data,1);

        // $imageContent = file_get_contents($imageUrl);

        // // Save the image to storage
        // Storage::disk('public')->put('images/' . $fileName, $imageContent);
        
        $error = array();
        $created_at = CommonModel::created_at();
        for($i = 0;$i < 4;$i++) {
            $check_cat = CategoryModel::where(array('name' => $cat_data[1]['cat_name'],'is_deleted' => 0))->get();
            if(!empty($check_cat->toArray())) {
                $category = $check_cat->toArray();
                // CommonModel::pr($check_cat,1);
                $cat_id = $category[0]['_id'];
                $check_brand = BrandModel::where(array('name' => $cat_data[1]['brand'],'is_deleted' => 0))->get();
                if(!empty($check_brand->toArray())) {
                    $brand = $check_brand->toArray();
                    $brand_id = $brand[0]['_id'];

                    $check_brand_cat = BrandCategoryModel::where(array('brand_id' => (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),'category_id' => (is_object($cat_id)) ? (string) $cat_id:$cat_id))->get();
                    if(empty($check_brand_cat)) {
                        if(BrandCategoryModel::create([
                            'brand_id'=> (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),
                            'category_id'=> (is_object($cat_id)) ? (string) $cat_id:$cat_id
                        ])) {
                        continue; 
                        } else {
                            $error[] = array('Erro concurred while inserting brand category model line 117');
                        }
                    }

                    $check_brand_model_number = BrandModelNumber::where(array('name' => $cat_data[1]['models'][0][$i]['model_name'],'is_deleted' => 0))->get();
                    if(empty($check_brand_model_number->toArray())) {
                        $model_insert_data = BrandModelNumber::create([
                            'name' => $cat_data[1]['models'][0][$i]['model_name'],
                            'brand_id'=> (string) $brand_id,
                            'category_id'=> (string) $cat_id,
                            'is_deleted'=> 0,
                            'status'=> 1,
                            'role'=> 1,
                            'created_at'=> $created_at,
                            'updated_at'=> $created_at,
                        ]);
                        if($model_insert_data == false) {
                            $error[] = array('Erro concurred while inserting brand category model line 134');
                        }
                    }
                } else {
                    $brand_id = BrandModel::create([
                        'name' => $cat_data[1]['brand'],
                        'is_deleted' => 0,
                        'role'=>1,
                        'status'=>1
                    ])->_id;
                    if($brand_id) {
                        $check_brand_cat = BrandCategoryModel::where(array('brand_id' => (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),'category_id' => (is_object($cat_id)) ? (string) $cat_id:$cat_id))->get();
                        if(empty($check_brand_cat)) {
                            BrandCategoryModel::create([
                                'brand_id'=> (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),
                                'category_id'=> (is_object($cat_id)) ? (string) $cat_id:$cat_id
                            ]);
                        }
                        
                        $check_brand_model_number = BrandModelNumber::where(array('name' => $cat_data[1]['models'][0][$i]['model_name'],'is_deleted' => 0))->get();
                        if(empty($check_brand_model_number->toArray())) {
                            $model_insert_data = BrandModelNumber::create([
                                'name' => $cat_data[1]['models'][0][$i]['model_name'],
                                'brand_id'=> (string) $brand_id,
                                'category_id'=> (string) $cat_id,
                                'is_deleted'=> 0,
                                'status'=> 1,
                                'role'=> 1,
                                'created_at'=> $created_at,
                                'updated_at'=> $created_at,
                            ]);
                            if($model_insert_data == false) {
                                $error[] = array('Erro concurred while inserting brand category model line 166');
                            }
                        }
                    } else {
                        $error[] = array('Erro concurred while inserting brand model line 170');
                    }

                }
            } else {
                $cat_id = CategoryModel::create([
                    'name' => $cat_data[1]['cat_name'],
                    'is_deleted' => 0,
                    'role'=>1,
                    'status'=>1
                ])->_id;
                if($cat_id) {
                    $check_brand = BrandModel::where(array('name' => $cat_data[1]['brand'],'is_deleted' => 0))->get();
                    if(!empty($check_brand->toArray())) {
                        $brand = $check_brand->toArray();
                        $brand_id = $brand[0]['_id'];

                        $check_brand_cat = BrandCategoryModel::where(array('brand_id' => (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),'category_id' => (is_object($cat_id)) ? (string) $cat_id:$cat_id))->get();
                        if(empty($check_brand_cat)) {
                            if(BrandCategoryModel::create([
                                'brand_id'=> (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),
                                'category_id'=> (is_object($cat_id)) ? (string) $cat_id:$cat_id
                            ])) {
                                continue;
                            } else {
                                $error[] = array('Erro concurred while inserting brand category model line 195');
                            }
                        }

                        $check_brand_model_number = BrandModelNumber::where(array('name' => $cat_data[1]['models'][0][$i]['model_name'],'is_deleted' => 0))->get();
                        if(empty($check_brand_model_number->toArray())) {
                            $model_insert_data = BrandModelNumber::create([
                                'name' => $cat_data[1]['models'][0][$i]['model_name'],
                                'brand_id'=> (string) $brand_id,
                                'category_id'=> (string) $cat_id,
                                'is_deleted'=> 0,
                                'status'=> 1,
                                'role'=> 1,
                                'created_at'=> $created_at,
                                'updated_at'=> $created_at,
                            ]);
                            if($model_insert_data == false) {
                                $error[] = array('Erro concurred while inserting brand category model line 212');
                            }
                        }
                    } else {
                        $brand_id = BrandModel::create([
                            'name' => $cat_data[1]['brand'],
                            'is_deleted' => 0,
                            'role'=>1,
                            'status'=>1
                        ])->_id;
                        if($brand_id) {
                            $check_brand_cat = BrandCategoryModel::where(array('brand_id' => (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),'category_id' => (is_object($cat_id)) ? (string) $cat_id:$cat_id))->get();
                            if(empty($check_brand_cat)) {
                                BrandCategoryModel::create([
                                    'brand_id'=> (is_object($brand_id)) ? $brand_id:new ObjectId($brand_id),
                                    'category_id'=> (is_object($cat_id)) ? (string) $cat_id:$cat_id
                                ]);
                            }

                            $check_brand_model_number = BrandModelNumber::where(array('name' => $cat_data[1]['models'][0][$i]['model_name'],'is_deleted' => 0))->get();
                            if(empty($check_brand_model_number->toArray())) {
                                $model_insert_data = BrandModelNumber::create([
                                    'name' => $cat_data[1]['models'][0][$i]['model_name'],
                                    'brand_id'=> (string) $brand_id,
                                    'category_id'=> (string) $cat_id,
                                    'is_deleted'=> 0,
                                    'status'=> 1,
                                    'role'=> 1,
                                    'created_at'=> $created_at,
                                    'updated_at'=> $created_at,
                                ]);
                                if($model_insert_data == false) {
                                    $error[] = array('Erro concurred while inserting brand category model line 232');
                                }
                            }

                        } else {
                            $error[] = array('Erro concurred while inserting brand model line 249');
                        }

                    }
                } else {
                    $error[] = array('Erro concurred while inserting category line 254');
                }
            }
        }
        if(!empty($error)) {
            echo json_encode(array('msg' => $error));
        } else {
            echo json_encode(array('msg' => 'success'));
        }
    }
}
