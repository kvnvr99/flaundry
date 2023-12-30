<div class="text-center">
    @if (isset($url_detail))
        <a href="{{ $url_detail }}" class="btn btn-sm btn-info waves-effect waves-light" title="Info">
            <i class="fas fa-exclamation-circle"></i>
        </a>
    @endif
    @if (isset($url_edit))
        <a href="{{ $url_edit }}" class="btn btn-sm btn-warning waves-effect waves-light" title="Edit">
            <i class="fas fa-align-left"></i>
        </a>
    @endif
    @if (isset($url_destroy))
        <a href="{{ $url_destroy }}" class="btn btn-sm btn-danger btn-delete waves-effect waves-light" title="Hapus">
            <i class="fas fa-trash"></i>
        </a>
    @endif

    <!-- costume button -->
    @if (isset($url_pilih_kurir))
        <a href="" class="btn btn-sm btn-primary btn_pop waves-effect waves-light" onClick="open_modal({{ $url_pilih_kurir }})" title="Pilih Kurir" data-toggle="modal">
            <i class="fas fa-truck"></i>
        </a>
    @endif

    @if (isset($url_kurir))
        <a href="" class="btn btn-sm btn-primary btn_pop waves-effect waves-light" onClick="open_modal({{ $url_kurir }})" title="Catatan Kurir" data-toggle="modal">
            <i class=" fas fa-check-square"></i>
        </a>
    @endif
    
    @if (isset($url_batal))
        <a href="{{ $url_batal }}" class="btn btn-sm btn-danger btn-delete waves-effect waves-light" title="Batalkan Pencatatan">
            <i class="fas fa-redo"></i>
        </a>
    @endif
</div>
<div class="text-left get-action">
    @if (isset($valid))
        <a href="#" data-id="{{ $valid }}" class="btn btn-sm btn-success waves-effect waves-light valid" title="Valid">
            <i class="far fa-check-circle"></i>
        </a>
    @endif
    @if (isset($invalid))
        <a href="#" data-id="{{ $valid }}" class="btn btn-sm btn-danger waves-effect waves-light invalid" title="Invalid">
            <i class="far fa-times-circle"></i>
        </a>
    @endif
    @if (isset($input_satuan))
        <input type="text" class="form-control quantity_satuan text-right"  name="quantity_qc" autocomplete="off" onkeypress="return isNumber(event)">
    @endif
    @if (isset($input_kg))
        <input class="form-control text-left quantity_kg text-right"  step=".01" maxlength="9" type="number" name="quantity_kg" autocomplete="off" />
    @endif


    @if (isset($url_like))
        <a href="{{ $url_like }}" class="btn btn-sm btn-outline-success btn_pop waves-effect waves-light" title="Suka">
            <i class="fe-thumbs-up"></i>
        </a>
    @endif

    @if (isset($url_dislike))
        <a href="{{ $url_dislike }}" class="btn btn-sm btn-outline-danger btn_pop waves-effect waves-light" title="Tidak Suka">
            <i class="fe-thumbs-down"></i>
        </a>
    @endif

    @if (isset($url_catatan))
        <a href="" class="btn btn-sm btn-info btn_pop waves-effect waves-light" onClick="open_modal({{ $url_catatan }})" title="Tambah Catatan" data-toggle="modal">
            <i class="fe-message-circle"></i>
        </a>
    @endif

    @if (isset($url_accept))
        <a href="{{ $url_accept }}" class="btn btn-sm btn-success waves-effect waves-light" title="Terima">
            <i class="fe-check-circle"></i>
        </a>
    @endif

</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.quantity_satuan').on('keyup', function(){
            if (this.value !== '') {
                let formattedValue = formatNumber(parseNumber(this.value));
                this.value = formattedValue;
            }
        });
        
        // $('.quantity_kg').on('input', function () {
        //     if (this.value !== '') {
        //         let formattedValue = formatNumber(parseNumber(this.value));
        //         this.value = formattedValue;
        //     }
        // });


        function formatRupiah(amount) {
            // Use Number.prototype.toLocaleString() to format the number as currency
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }
    
        function parseRupiah(rupiahString) {
            // Remove currency symbol, separators, and parse as integer
            return parseInt(rupiahString.replace(/[^\d]/g, ''));
        }
    
    
        function formatNumber(number) {
            // Use Number.prototype.toLocaleString() to format the number as currency
            return Number(number).toLocaleString('id-ID');
        }
    
        function parseNumber(number) {
            // Remove currency symbol, separators, and parse as integer
            // Replace dot only if it exists in the number
            return parseInt(number.replace(/[^\d]/g, '').replace('.', ''));
        }
    });
</script>
