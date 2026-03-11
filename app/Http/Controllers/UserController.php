<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;

class UserController extends Controller
{
    // Redirect based on user type
    public function Dashboard()
    {
        $user = Auth::user();

        if ($user->type === 'admin') {
            $categories   = Category::all();
            $suppliers    = Supplier::all();
            $products     = Product::latest()->take(10)->get();
            $orders       = Order::latest()->take(10)->get();
            $totalCategories = Category::count();
            $totalSuppliers  = Supplier::count();
            $totalProducts   = Product::count();
            $totalOrders     = Order::count();

            return view('admin.dashboard', compact(
                'categories', 'suppliers', 'products', 'orders',
                'totalCategories', 'totalSuppliers', 'totalProducts', 'totalOrders'
            ));
        }

        return redirect()->route('user.dashboard');
    }

    // User dashboard
    public function userDashboard()
    {
        $products      = Product::latest()->take(6)->get();
        $myOrders      = Order::where('customer_email', Auth::user()->email)->latest()->take(5)->get();
        $totalMyOrders = Order::where('customer_email', Auth::user()->email)->count();

        return view('user.dashboard', compact('products', 'myOrders', 'totalMyOrders'));
    }

    // User view all products
    public function userProducts()
    {
        $products = Product::all();
        return view('user.products', compact('products'));
    }

    // User view their orders
    public function myOrders()
    {
        $orders = Order::where('customer_email', Auth::user()->email)->latest()->get();
        return view('user.myorders', compact('orders'));
    }

    // User place order form
    public function placeOrder($id)
    {
        $product = Product::findOrFail($id);
        return view('user.placeorder', compact('product'));
    }

    // User submit order
    public function postPlaceOrder(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $subTotal = $product->selling_price * $request->quantity;
        $vat      = $subTotal * 0.10;
        $total    = $subTotal + $vat;

        $order = new Order();
        $order->order_no        = 'ORD-' . time();
        $order->customer_name   = Auth::user()->name;
        $order->customer_email  = Auth::user()->email;
        $order->customer_phone  = $request->customer_phone;
        $order->customer_address = $request->customer_address;
        $order->sub_total       = $subTotal;
        $order->vat             = $vat;
        $order->total           = $total;
        $order->payment_status  = 'pending';
        $order->order_status    = 'pending';
        $order->save();

        $orderDetail = new OrderDetail();
        $orderDetail->order_id      = $order->id;
        $orderDetail->product_name  = $product->product_name;
        $orderDetail->product_qty   = $request->quantity;
        $orderDetail->product_price = $product->selling_price;
        $orderDetail->total         = $subTotal;
        $orderDetail->save();

        return redirect()->route('user.myorders')->with('success', 'Order placed successfully!');
    }
}