<div>
    <select class="form-control select-search text-bold" id="bahan" name="bahan"></select>
</div>


@push('scripts')
<script type="text/javascript">
    // document.addEventListener('livewire:load', function () {
        // $('#bahan').select2({
        //     minimumInputLength: 2,
        //     placeholder: 'Cari Bahan',
        //     ajax: {
        //         url: "{{ url('getproduk') }}",
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results:  $.map(data, function (item) {
        //                     return {
        //                     text: item.nama_produk,
        //                     id: item.id,
        //                     }

        //                 })
        //             };
        //         },
        //         cache: true,
        //     }
        // });
        // console.log('asas')
    })
</script>
@endpush