<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bills;
use App\Models\Bill_Detail;
use App\Models\Customer;


class BillController extends Controller
{
    //
    public function listBillAll(){
        $bills=Bills::orderBy('created_at', 'desc')->get();
        return view('admin.bill.listbill',compact('bills'));
    }
    public function getBillList(Request $request, $status){
        $status = $request->input('status');
        $billstatus = Bills::where('status','Like','%'.$status.'%')->orderBy('updated_at', 'desc')->get();
        return view('admin.bill.listbill', compact('billstatus', 'status'));
    }
    public function updateBillStatus(Request $request,$id,$status){
        $bill=Bills::where('id',$id)->get();
        $billdetail=Bill_Detail::where('id_bill',$id)->get();
        return view('admin.bill.updatebill',array('bill'=>$bill),compact('billdetail'));
    }
    public function updateBillStatusAjax(Request $request, string $id){
        $bill=Bills::find($id);
        $bill->status=$request->status;
        $bill->save();
        return redirect()->route('admin.listBillAll')->with('success','Bạn đã sữa thành công!'); 
    }
    public function cancelBill(string $id){
        $bill=Bills::find($id);
        $idcustomer = $bill->id_customer;
        $billdetail = Bill_Detail::where('id_bill', $id)->get();
        $customer = Customer::find($idcustomer);
        foreach ($billdetail as $detail) {
            $detail->delete();
        }
        $customer->delete();
        $bill->delete();        
        return redirect()->route('admin.listBillAll')->with('success', 'Bạn đã xóa thành công!');
    }
}
