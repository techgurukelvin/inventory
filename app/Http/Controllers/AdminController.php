<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;

class AdminController extends Controller
{
    public function adminTest()
    {
        return 'i am admin';
    }

    public function addcategory()
    {
        return view('admin.addcategory');
    }

    public function postAddcategory(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->back()->with('success', 'Category added!');
    }

    public function viewcategory()
    {
        $categories = Category::all();
        return view('admin.viewcategory', compact('categories'));
    }

    public function deletecategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted!');
    }

    public function updatecategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.updatecategory', compact('category'));
    }

    public function postupdatecategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->category_name = $request->category_name;
        $category->save();
        return redirect()->route('admin.viewcategory')->with('success', 'Category updated!');
    }

    public function addsupplier()
    {
        return view('admin.addsupplier');
    }

    public function postAddsupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->save();
        return redirect()->back()->with('success', 'Supplier added!');
    }

    public function viewsupplier()
    {
        $suppliers = Supplier::all();
        return view('admin.viewsupplier', compact('suppliers'));
    }

    public function deletesupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier deleted!');
    }

    public function updatesupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.updatesupplier', compact('supplier'));
    }

    public function postupdatesupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->save();
        return redirect()->route('admin.viewsupplier')->with('success', 'Supplier updated!');
    }

    public function addproduct()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.addproduct', compact('categories', 'suppliers'));
    }

    public function postAddproduct(Request $request)
    {
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_code = $request->product_code;
        $product->product_quantity = $request->product_quantity;
        $product->product_category = $request->product_category;
        $product->product_supplier = $request->product_supplier;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->product_description = $request->product_description;

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->product_image = $imageName;
        }

        $product->save();
        return redirect()->back()->with('success', 'Product added!');
    }

    public function viewproduct()
    {
        $products = Product::all();
        return view('admin.viewproduct', compact('products'));
    }

    public function deleteproduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted!');
    }

    public function updateproduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.updateproduct', compact('product', 'categories', 'suppliers'));
    }

    public function postupdateproduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_code = $request->product_code;
        $product->product_quantity = $request->product_quantity;
        $product->product_category = $request->product_category;
        $product->product_supplier = $request->product_supplier;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->product_description = $request->product_description;

        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $imageName);
            $product->product_image = $imageName;
        }

        $product->save();
        return redirect()->route('admin.viewproduct')->with('success', 'Product updated!');
    }
   

    public function addorder()
    {
        $products = Product::all();
        return view('admin.addorder', compact('products'));
    }

    public function postAddorder(Request $request)
    {
        $order = new Order();
        $order->order_no = 'ORD-' . time();
        $order->customer_name = $request->customer_name;
        $order->customer_email = $request->customer_email;
        $order->customer_phone = $request->customer_phone;
        $order->customer_address = $request->customer_address;
        $order->sub_total = $request->sub_total;
        $order->vat = $request->vat;
        $order->total = $request->total;
        $order->payment_status = $request->payment_status;
        $order->order_status = $request->order_status;
        $order->save();

        $product_names = $request->product_name;
        $product_qtys = $request->product_qty;
        $product_prices = $request->product_price;
        $product_totals = $request->product_total;

        for ($i = 0; $i < count($product_names); $i++) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_name = $product_names[$i];
            $orderDetail->product_qty = $product_qtys[$i];
            $orderDetail->product_price = $product_prices[$i];
            $orderDetail->total = $product_totals[$i];
            $orderDetail->save();
        }

        return redirect()->route('admin.vieworder')->with('success', 'Order added!');
    }

        public function vieworder()
        {
            $orders = Order::all();
            return view('admin.vieworder', compact('orders'));
        }

    public function deleteorder($id)
    {
        $order = Order::findOrFail($id);
        OrderDetail::where('order_id', $id)->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted!');
    }

    public function orderdetails($id)
    {
        $order = Order::findOrFail($id);
        $orderDetails = OrderDetail::where('order_id', $id)->get();
        return view('admin.orderdetails', compact('order', 'orderDetails'));
    }

    public function updateorderstatus($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.updateorderstatus', compact('order'));
    }

    public function postupdateorderstatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->order_status = $request->order_status;
        $order->save();
        return redirect()->route('admin.vieworder')->with('success', 'Order status updated!');
    }
   

    // Manage Users
    public function manageusers()
    {
        $users = User::where('type', '!=', 'admin')->get();
        return view('admin.manageusers', compact('users'));
    }

    public function deleteuser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted!');
    }

    public function updateusertype($id)
    {
        $user = User::findOrFail($id);
        return view('admin.updateusertype', compact('user'));
    }

    public function postupdateusertype(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->type = $request->type;
        $user->save();
        return redirect()->route('admin.manageusers')->with('success', 'User type updated!');
    }
}