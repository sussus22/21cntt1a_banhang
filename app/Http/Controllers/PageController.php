<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Bills;
use App\Models\Bill_Detail;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    public function getSanPham(){
        $new_products=Product::where('new',1)->get();
        $products=Product::all();
        return view('index',compact('new_products','products'));
    }
    public function show(int $id)
    {
        $product = Product::find($id);
        return view('product', array('product' => $product));
    }
    //thêm 1 sản phẩm có id cụ thể vào model cart rồi lưu dữ liệu của model cart vào 1 session có tên cart (session được truy cập bằng thực thể Request)
    public function addToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->add($product,$id);
        $request->session()->put('cart',$cart);
        return redirect()->back();
    }

    //thêm 1 sản phẩm có số lượng >1 có id cụ thể vào model cart rồi lưu dữ liệu của model cart vào 1 session có tên cart (session được truy cập bằng thực thể Request)
    public function addManyToCart(Request $request,$id){
        $product=Product::find($id);
        $oldCart=Session('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->addMany($product,$id,$request->qty);
        $request->session()->put('cart',$cart);
       
        return redirect()->back();
    }


    public function delCartItem($id){
        $oldCart=Session::has('cart')?Session::get('cart'):null;
        $cart=new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items)>0){
            Session::put('cart',$cart);
        }else Session::forget('cart');
        return redirect()->back();
    }
    public function getCheckout(){
        return view('checkout');
    }
    public function Checkout(Request $request){
        if($request->input('payment_method')!="VNPAY"){
            $cart=Session::get('cart');
            $customer=new Customer();
            $customer->name=$request->input('name');
            $customer->gender=$request->input('gender');
            $customer->email=$request->input('email');
            $customer->address=$request->input('address');
            $customer->phone_number=$request->input('phone_number');
            $customer->note=$request->input('note');
            $customer->save();
    
            $bill=new Bills();
            $bill->id_customer=$customer->id;
            $bill->date_order=date('Y-m-d');
            $bill->total=$cart->totalPrice;
            $bill->payment=$request->input('payment_method');
            $bill->note = $request->input('note');
            $bill->status="đang chuẩn bị hàng";
            $bill->save();
    
            foreach($cart->items as $key=>$value)
            {
                $bill_detail=new Bill_Detail();
                $bill_detail->id_bill=$bill->id;
                $bill_detail->id_product=$key;
                $bill_detail->quantity=$value['qty'];
                $bill_detail->unit_price=$value['price']/$value['qty'];
                $bill_detail->save();
            }
            Session::forget('cart');
            return redirect()->back()->with('success','Đặt hàng thành công');
    
        }
        else {//nếu thanh toán là vnpay
            $cart=Session::get('cart');
            return view('/vnpay-index',compact('cart'));
        }
    }
    public function getSignin(){
        return view('signup');
    }
    public function postSignin(Request $req){
        $this->validate($req,
        ['email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:20',
            'fullname'=>'required',
            'repassword'=>'required|same:password',
            'phone'=>'required|min:10'
        ],
        ['email.required'=>'Vui lòng nhập email',
        'email.email'=>'Không đúng định dạng email',
        'email.unique'=>'Email đã có người sử  dụng',
        'password.required'=>'Vui lòng nhập mật khẩu',
        'repassword.same'=>'Mật khẩu không giống nhau',
        'password.min'=>'Mật khẩu ít nhất 6 ký tự',
        'phone.required'=>'Số điện thoại ít nhất 10 ký tự',
        'phone.min'=>'Số điện thoại ít nhất 10 kí tự'
        ]);

        $user=new User();
        $user->full_name=$req->fullname;
        $user->email=$req->email;
        $user->password=Hash::make($req->password);
        $user->phone=$req->phone;
        $user->address=$req->address;
        $user->level=3;  //level=1: admin; level=2:kỹ thuật; level=3: khách hàng
        $user->save();
        return redirect()->back()->with('success','Tạo tài khoản thành công');
    }
}
