<template>
    <div>
        <div class="panel panel-flat border-top-xlg border-top-green-600">
            <div class="panel-heading">
                <h5 class="panel-title"><span class="text-semibold"><i class="icon-table2"></i> Daftar Produk Keluar</span></h5>
            </div>
            <div class="panel-body">
                <div class="pull-left">
                    <div class="col-sm-4">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="ID Produk Keluar" v-model="id_produk_keluar" v-on:keyup="getDataTransaksiKeluar">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-size-base"></i>
                            </div>
                        </div>
                    </div>
                    <date-picker v-model="range_date" @change="getDataTransaksiKeluar" :clearable="false" type="date" value-type="format" format="DD-MM-YYYY" range placeholder="Select date range"></date-picker>
                </div>
                <div class="pull-right">
                    <a v-on:click.prevent="showPanel" href="" class="btn bg-green-600 btn-labeled text-bold"><b><i class="icon-plus-circle2"></i></b> Buat Produk Keluar</a>	
                </div>
            </div>
            
            <table class="table table-responsive datatable-basic table-xs table-bordered table-framed">
                <thead class="bg-slate-800 text-semibold">
                    <tr>
                        <th class="text-center" width="200">ID Produk Keluar</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center" width="200">By</th>
                        <th class="text-center" width="110"><i class="icon-gear"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="!data_transaksi.length">
                        <tr>
                            <td class="text-center text-bold" colspan="4">Data Transaksi Tidak Ditemukan</td>                    
                        </tr>
                    </template>
                    <template v-else>
                    <tr v-for="(transaksi, i) in data_transaksi" :key="transaksi.id">
                        <td class="text-center text-bold">{{ transaksi.kode_transaksi }}</td>
                        <td class="text-center text-bold">{{ transaksi.tgl_keluar | moment }}</td>
                        <td class="text-center text-bold">{{ transaksi.user.name }}</td>
                        <td class="text-center action">
                            <button v-on:click="show_transaksi(i)" type="button" class="btn btn-link"><i class="text-primary icon-eye"></i></button>
                            <button v-on:click="edit_transaksi(i)" type="button" class="btn btn-link"><i class="text-danger icon-pencil5"></i></button>
                            <button v-on:click="delete_transaksi(transaksi.id)" type="button" class="btn btn-link"><i class="text-danger icon-trash"></i></button>
                        </td>
                    </tr>
                    </template>
                </tbody>
            </table>

            <div class="panel-footer">
                <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                <div class="heading-elements">
                    <span class="heading-text text-semibold"></span>
                </div>
            </div>
        </div>
        <slideout-panel></slideout-panel>
    </div>
</template>
<style>
    table tbody tr td.action button {
        display: inline-block;
        padding: 0;
    }
</style>
<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import moment from 'moment'
    import createkeluar from './Create';
    import axios from 'axios';
    import Swal from 'sweetalert2'
    import 'sweetalert2/src/sweetalert2.scss'

    export default {
        components: { DatePicker, createkeluar},
        data(){
            return {
                range_date: [moment().format('DD-MM-YYYY'), moment().format('DD-MM-YYYY')],
                id_produk_keluar : '',
                data_transaksi : '',
            }
        },
        mounted(){ 
            this.getDataTransaksiKeluar();
        },
        methods: {
            showPanel() {
                const panelResult = this.$showPanel({
                    component: "createkeluar",
                    openOn: 'right',
                    width: 1100
                });

                panelResult.promise
                .then(result => {
                    this.getDataTransaksiKeluar();
                    console.log(result)
                });
            },
            getDataTransaksiKeluar(){
                axios.get(window.location.origin + '/transaksi/produk-keluar?ajax=true&startdate='+ this.range_date[0] + "&enddate=" + this.range_date[1] + "&kodetransaksi=" + this.id_produk_keluar)
                .then(response => {
                    this.data_transaksi = response.data;
                })
                .catch(error => {
                    alert('gagal')
                })
            },
            delete_transaksi(id){
                swal({
						title: 'Peringatan!',
						text: "Apakah Anda Yakin Ingin Menghapus Data ?",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Ya, Hapus!',
						cancelButtonText: 'Batal'
					}).then((result) => {
						if (result.value) {
							axios.delete(window.location.origin + '/transaksi/produk-keluar/' + id)
                            .then(response => {
                                this.getDataTransaksiKeluar();
                            })
                            .catch(error => {
                                alert('gagal')
                            })
						}
					})
            }
        },
        filters: {
            moment: function (date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY')
            }
        }
    }
</script>
