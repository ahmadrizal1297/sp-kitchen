<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use App\Menu;
use App\MenuOrder;
use Illuminate\Http\Request;

class MakeOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($code) {
        $order = Order::with(['user:id,name,phone', 'package:id,name,type,total_items', 'menus'])->where('code', $code)->first();
        $menuOrder = new MenuOrder();
        $menus = Menu::where('type', $order->package->type)->get(['name', 'price']);
        $tambahans = $menuOrder->daftar['optional'];

        return view('pages.make-order.index', [
          'tambahans' => $tambahans,
          'menus' => $menus,
          'order' => $order,
          'orders' => $order->menus,
          'code' => $code,
          'limit' => $order->package->total_items - $order->menus()->count(),
          'isEvent' => $order->package->total_items == 0
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $orderCount = Order::whereDate('created_at', '=', date('Y-m-d'))->count();
      $code = "PKG".sprintf('%02d', $request->package).date("dmy").sprintf('%04d', $orderCount + 1);
      $clock = date("H.i", time());

      $customer = User::where('phone', $request->phone)->first();
      if ($customer==null) {
        $customer = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'gender' => $request->gender,
          'phone' => $request->phone,
        ]);
      }

      try {
        $order = $customer->orders()->create([
          'code' => $code,
          'kecamatan' => $request->kecamatan,
          'package_id' => $request->package,
          'customer_id' => $customer->id,
          'waktu' => $request->waktu
        ]);
        if ($order) {
          return redirect(route('make-order.index', $code));
        }
      } catch (\Throwable $th) {
        return abort(400, $th);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $code)
    {
      $order = Order::where('code', $code)->first();
      try {
        $result = $order->update([
          'status' => 'confirmed',
          'alamat' => $request->alamat,
          'payment_method' => $request->pembayaran
        ]);
        if ($result) {
          return redirect(route('landing-page'))->with([
            'status' => 'Silahkan tunggu konfirmasi dari admin via WA',
            'success' => true
          ]);
        }
      } catch (Exception $e) {
        return redirect(route('landing-page'))->with([
          'status' => 'Pesanan gagal di lanjutkan',
          'success' => true
        ]);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $code)
    {
      $order = Order::where('code', $code)->first();
      $menu = Menu::where('name', $request->menu)->first();
      $clock = date("H.i", time());

      if ($clock < 17) {
        if ($order->menus()->count() < $order->package->total_items) {
          try {
            $order->menus()->attach($menu->id, [
              'antar' => $request->antar,
              'optional' => $request->optional,
            ]);
            return redirect(route('make-order.index', $code))->with([
              'status' => 'Pesanan berhasil ditambahkan',
              'success' => true
            ]);
          } catch (Exception $e) {
            return redirect(route('make-order.index', $code))->with([
              'status' => 'Gagal menambah pesanan',
              'success' => false
            ]);
          }
        }
      } else {
        return redirect(route('make-order.index', $code))->with([
          'status' => 'Maaf kami sudah tutup untuk hari ini',
          'success' => false
        ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($code, $menuId, $antar)
    {
      $order = Order::where('code', $code)->first();
      $menuOrder = MenuOrder::where([
        'order_id' => $order->id,
        'menu_id' => $menuId,
        'antar' => $antar
      ])->first();
      try {
        $result = $menuOrder->delete();
        if ($result) {
          return redirect(route('make-order.index', $code))->with([
            'status' => 'Berhasil menghapus barang',
            'success' => true
          ]);
        }
      } catch (Exception $e) {
        return redirect(route('make-order.index', $code))->with([
          'status' => 'Gagal menghapus barang',
          'success' => false
        ]);
      }
    }
}
