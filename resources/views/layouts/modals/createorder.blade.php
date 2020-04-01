<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-notification" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
    <div class="modal-content bg-gradient-info">
      <div class="modal-header">
        <h6 class="modal-title" id="modal-title-notification">Daftar untuk buat pesanan baru.</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('make-order.store')}}" method="POST">
          @csrf
          <div class="form-group">
            <label for="name" class="form-label">Nama</label>
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input class="form-control" placeholder="John Smith" type="text" name="name">
            </div>
          </div>
          <div class="form-group">
            <label for="phone" class="form-label">No Telepon</label>
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
              </div>
              <input class="form-control" placeholder="08xxxx" type="email" name="email">
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" placeholder="Email" type="email" name="email">
            </div>
          </div>
          <div class="form-group">
            <label for="name" class="form-label">Paket</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-box-open"></i></span>
              </div>
              <select id="package" class="form-control" name="package">
                @foreach ($packages as $package)
                  <option value="{{ $package['id'] }}">
                  {{ $package['name'] }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link text-white">Input Kode Pesanan</button>
        <a href="" type="button" class="btn btn-white ml-auto">Buat pesanan</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-aform" tabindex="-1" role="dialog" aria-labelledby="modal-form" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div class="card-body px-lg-5 py-lg-5">
          <div class="text-center text-muted mb-4">
            Daftar untuk buat pesanan.
          </div>
          <form role="form">
            <div class="form-group mb-3">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                </div>
                <input class="form-control" placeholder="Email" type="email">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                </div>
                <input class="form-control" placeholder="Password" type="password">
              </div>
            </div>
            <div class="text-center">
              <a href="/makeorder" type="button" class="btn btn-block btn-info my-4">Lanjut buat pesanan</a>                    
            </div>
            <div class="text-center">
              <button type="button" class="btn btn-block btn-info my-4">Sudah pernah daftar?</button>                    
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
