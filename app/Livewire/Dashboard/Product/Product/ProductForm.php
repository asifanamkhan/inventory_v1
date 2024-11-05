<?php

namespace App\Livewire\Dashboard\Product\Product;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    public $photos = [];
    public $editPhotos = [];
    public $edit_select = [];
    public $state = [];
    public $variant_cart = [];
    public $product_id, $product_categories, $product_brands, $product_units;
    public $u_code;
    public function mount($u_code = null)
    {
        if ($u_code) {
            $products = DB::table('product')
                ->where('u_code', $u_code)
                ->get();

            $this->u_code = $u_code;

            $this->state['name'] = $products[0]->name;
            $this->state['description'] = $products[0]->description;
            $this->state['brand_id'] = $products[0]->brand_id;
            $this->state['category_id'] = $products[0]->category_id;
            $this->state['unit_id'] = $products[0]->unit_id;
            $this->state['variant_type'] = $products[0]->variant_type;
            $this->state['u_code'] = $products[0]->u_code;
            $this->state['images'] = $products[0]->images;

            foreach ($products as $key => $value) {
                $this->variant_cart[] = [
                    'description' => $value->variant_description,
                    'barcode' => $value->barcode,
                    'purchase_price' => $value->purchase_price,
                    'sale_price' => $value->sale_price,
                    'open_stock' => $value->open_stock,
                    'alert_qty' => $value->alert_qty,
                    'id' => $value->id
                ];
            }

            if ($this->state['images'] != 0) {
                $this->editPhotos = json_decode($this->state['images']);
            }

            $this->edit_select['edit_brand_id'] = $products[0]->brand_id;
            $this->edit_select['edit_category_id'] = $products[0]->category_id;
            $this->edit_select['edit_unit_id'] = $products[0]->unit_id;
        } else {
            $this->state['variant_type'] = 1;
            $this->state['images'] = 0;

            $this->variant_cart[] = [
                'description' => '',
                'barcode' => '',
                'purchase_price' => '',
                'sale_price' => '',
                'open_stock' => '',
                'alert_qty' => '',
                'id' => 0,
            ];
        }



        $this->productCategory();
        $this->productBrand();
        $this->productUnit();
    }

    public function productCategory()
    {
        $this->product_categories = DB::table('product_category')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function productBrand()
    {
        return $this->product_brands = DB::table('brand')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function productUnit()
    {
        return $this->product_units = DB::table('unit')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function addVarient()
    {
        $this->variant_cart[] = [
            'description' => '',
            'barcode' => '',
            'purchase_price' => '',
            'sale_price' => '',
            'open_stock' => '',
            'alert_qty' => '',
            'id' => 0,
        ];
    }

    public function removeVarient($key)
    {
        unset($this->variant_cart[$key]);
    }

    public function save()
    {

        Validator::make($this->state, [
            'brand_id' => 'required',
            'category_id' => 'required',
            'name' => 'required',
        ])->validate();

        Validator::make($this->variant_cart, [
            "purchase_price.*" => 'required|distinct|min:1',
            "sale_price.*" => 'required|distinct|min:1',

        ])->validate();

        if (count($this->photos)) {
            foreach ($this->photos as $photo) {
                $file_name = time() . '-' . mt_rand() . '.' . $photo['extension'];
                Storage::putFileAs('upload/product', new File($photo['path']), $file_name);
                $allFile[] = $file_name;
            }
            $this->state['images'] = json_encode($allFile);
        }

        if ($this->u_code) {
            foreach ($this->variant_cart as $cart) {
                $product_exist = DB::table('product')
                    ->where('id', $cart['id'])
                    ->exists();

                if ($product_exist) {
                    DB::table('product')
                        ->where('id', $cart['id'])
                        ->update([
                            'name' => $this->state['name'],
                            'branch_id' => 1,
                            'images' => $this->state['images'],
                            'brand_id' => $this->state['brand_id'],
                            'unit_id' => $this->state['unit_id'],
                            'category_id' => $this->state['category_id'],
                            'u_code' => $this->u_code,
                            'description' => $this->state['description'],
                            'variant_type' => $this->state['variant_type'],
                            'variant_description' => $cart['description'],
                            'barcode' => $cart['barcode'],
                            'purchase_price' => $cart['purchase_price'],
                            'sale_price' => $cart['sale_price'],
                            'open_stock' => $cart['open_stock'],
                            'alert_qty' => $cart['alert_qty'],
                            'updated_by' => Auth::user()->id,
                        ]);

                    DB::table('product_tran_dtl')
                    ->where('product_id', $cart['id'])
                        ->update([
                            'ref_id' => $cart['id'],
                            'branch_id' => 1,
                            'product_id' => $cart['id'],
                            'type' => 'op',
                            'quantity' => $cart['open_stock'],
                            'ref_memo' => $cart['id'],
                            'updated_by' => Auth::user()->id,
                    ]);
                } else {

                    $product_id = DB::table('product')->insertGetId([
                        'name' => $this->state['name'],
                        'branch_id' => 1,
                        'images' => $this->state['images'],
                        'brand_id' => $this->state['brand_id'],
                        'unit_id' => $this->state['unit_id'],
                        'category_id' => $this->state['category_id'],
                        'u_code' => $this->u_code,
                        'description' => $this->state['description'],
                        'variant_type' => $this->state['variant_type'],
                        'variant_description' => $cart['description'],
                        'barcode' => $cart['barcode'],
                        'purchase_price' => $cart['purchase_price'],
                        'sale_price' => $cart['sale_price'],
                        'open_stock' => $cart['open_stock'],
                        'alert_qty' => $cart['alert_qty'],
                        'created_by' => Auth::user()->id,
                    ]);

                    DB::table('product_tran_dtl')->insert([
                        'ref_id' => $product_id,
                        'branch_id' => 1,
                        'product_id' => $product_id,
                        'type' => 'op',
                        'quantity' => $cart['open_stock'],
                        'ref_memo' => $product_id,
                        'created_by' => Auth::user()->id,

                    ]);
                }
            }
        } else {

            $u_code = time() . '-' . mt_rand(1000, 9999);
            $this->productInsert($u_code);
        }



        session()->flash('status', 'New product added successfully.');
        $this->reset();
        return $this->redirect(route('product'), navigate: true);
    }

    function productInsert($u_code)
    {
        foreach ($this->variant_cart as $cart) {
            $product_id = DB::table('product')->insertGetId([
                'name' => $this->state['name'],
                'branch_id' => 1,
                'images' => $this->state['images'],
                'brand_id' => $this->state['brand_id'],
                'unit_id' => $this->state['unit_id'],
                'category_id' => $this->state['category_id'],
                'u_code' => $u_code,
                'description' => $this->state['description'],
                'variant_type' => $this->state['variant_type'],
                'variant_description' => $cart['description'],
                'barcode' => $cart['barcode'],
                'purchase_price' => $cart['purchase_price'],
                'sale_price' => $cart['sale_price'],
                'open_stock' => $cart['open_stock'],
                'alert_qty' => $cart['alert_qty'],
                'created_by' => Auth::user()->id,
            ]);

            DB::table('product_tran_dtl')->insert([
                'ref_id' => $product_id,
                'branch_id' => 1,
                'product_id' => $product_id,
                'type' => 'op',
                'quantity' => $cart['open_stock'],
                'ref_memo' => $product_id,
                'created_by' => Auth::user()->id,

            ]);
        }
    }
    public function render()
    {
        return view('livewire.dashboard.product.product.product-form');
    }
}