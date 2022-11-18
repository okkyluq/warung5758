<template>
    <div class="row">
        <div class="col-md-12">
							

            <div class="panel panel-success" style="border-bottom: none;">
                <div class="panel-heading" style="border-top-right-radius: 0; border-top-left-radius:0;">
                    <h6 class="panel-title">Item Keluar<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                    <div class="heading-elements">
                        <button v-on:click.prevent="closePanel" type="button" class="btn btn-danger"><i class="icon-circle-left2 position-left"></i> Batal</button>
                        <button v-on:click.prevent="submitForm" type="button" class="btn btn-primary"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                    </div>
                </div>

                <div class="panel-body">
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ID Produk Keluar :</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" placeholder="ID Produk Keluar" v-model="form.kode_transaksi" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tanggal :</label>
                            <div class="col-sm-3">
                                <date-picker type="date" value-type="format" format="DD-MM-YYYY" placeholder="Pilih Tanggl Item Keluar" v-model="form.tgl_keluar"></date-picker>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Catatan :</label>
                            <div class="col-lg-9">
                                <textarea rows="3" cols="3" placeholder="Tambahkan Catatan" class="form-control" v-model="form.catatan"></textarea>
                            </div>
                        </div>
                        <div class="alert alert-danger alert-styled-right alert-bordered" v-if="errors.list_item">
                            <span class="text-semibold">{{ errors.list_item[0] }}</span></a>
                        </div>
                        <div class="table">
							<table class="table">
								<thead>
									<tr>
										<th class="col-lg-4 text-left">Nama Item</th>
										<th class="col-lg-1 text-center">Jumlah</th>
										<th class="col-lg-1 text-center">Satuan</th>
										<th class="col-lg-2 text-center">Harga Beli</th>
										<th class="col-lg-2 text-center">Total Harga Beli</th>
										<th class="col-lg-1 text-center"><i class="icon-gear"></i></th>
									</tr>
								</thead>
								<tbody>
                                    <template v-if="!form.list_item.length">
                                    <tr>
                                        <td class="text-center text-bold" colspan="6">Item Belum Ada</td>
                                    </tr>
                                    </template>
                                    <template v-else>
							        <tr v-for="(item, i) in form.list_item" :key="item.id">
						                <td>
                                            <input type="hidden" id="item_id" name="item_id" v-model="item.item_id">
                                            <v-select label="nama_item" :options="options" @search="onSearch" @input="bahan => setSelected(item, bahan)" v-model="item.nama_item" placeholder="Silahkan Pilih Produk">
                                                <template slot="no-options">
                                                    Silahkan Masukan Produk
                                                </template>
                                            </v-select>
                                            <div class="label-block" v-if="errors['list_item.'+i+'.item_id']">
                                                <span class="help-block text-danger text-bold">{{errors['list_item.'+i+'.item_id'][0]}}</span>
                                            </div>
                                        </td>
						                <td>
                                            <vue-numeric 
                                                class="form-control text-right qty"
                                                v-bind:minus="false" 
                                                separator="," 
                                                v-model="item.jumlah">
                                            </vue-numeric>
                                            <div class="label-block" v-if="errors['list_item.'+i+'.jumlah']">
                                                <span class="help-block text-danger">{{errors['list_item.'+i+'.jumlah'][0]}}</span>
                                            </div>
                                        </td>
						                <td class="text-center text-bold">
                                             {{item.satuan ? item.satuan : '-'}}
                                        </td>
						                <td>
                                            <vue-numeric 
                                                class="form-control text-right qty"
                                                v-bind:minus="false" 
                                                separator="," 
                                                v-model="item.harga_beli">
                                            </vue-numeric>
                                            <div class="label-block" v-if="errors['list_item.'+i+'.harga_beli']">
                                                <span class="help-block text-danger">{{errors['list_item.'+i+'.harga_beli'][0]}}</span>
                                            </div>
                                        </td>
						                <td>
                                            <vue-numeric 
                                                class="form-control text-right qty disabled"
                                                v-bind:minus="false" 
                                                separator="," 
                                                currency="Rp."
                                                readonly
                                                v-model="item.total_harga_beli">
                                            </vue-numeric>
                                        </td>
						                <td class="text-center">
                                            <button v-on:click="remove_line(i)" type="button" class="btn btn-link"><i class="text-danger icon-trash"></i></button>
                                        </td>
						            </tr>
                                    </template>
					            </tbody>
							</table>
						</div>
                         <div class="form-group has-feedback" style="margin-top: 10px;">
                            <button type="button" class="btn btn-link btn-xs text-info" v-on:click="add_line"><i class="icon-plus-circle2 position-left text-info"></i> Tambah Produk</button>
                        </div>


                    </form>

                </div>




            </div>

            
        </div>
        </div>
</template>
<style>
</style>
<script>
    import 'vue2-datepicker/index.css';
    import 'vue-select/dist/vue-select.css';
    import vSelect from 'vue-select'
    import VueNumeric from 'vue-numeric'
    import axios from 'axios';
    import moment from 'moment'

    export default {
        components: {vSelect, VueNumeric},
        data(){
            return {
                errors:'',
                options: [],
                form: {
                    kode_transaksi: '',
                    tgl_keluar: moment().format('DD-MM-YYYY'),
                    catatan: '',
                    list_item: []
                }
            }
        },
        mounted(){
            this.getItemKeluarID()
        },
        methods: {
            getItemKeluarID: function(){
                axios.get(window.location.origin + '/getitemkeluarid')
                .then(response => {
                    this.form.kode_transaksi = response.data
                })
                .catch(error => {
                    alert('gagal mengambil kode keluar')
                })
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            add_line() {
                this.form.list_item.push({
                    item_id: '',
                    nama_item: '',
                    jumlah: '',
                    satuan: '',
                    harga_beli: '',
                    total_harga_beli: '',
                });
            },
            remove_line(id){
                this.form.list_item.splice(id,1);
            },
            closePanel() {
                this.$emit("closePanel", {ghilman : 1});
            },
            onSearch(search, loading) {
                if(search.length) {
                    loading(true);
                    this.search(loading, search, this);
                }
            },
            search:(loading, search, vm) => {
                fetch(
                    window.location.origin + `/getitem?q=${escape(search)}`
                ).then(res => {
                    res.json().then(json => (vm.options = json.items));
                    loading(false);
                });
            },
            setSelected(item, bahan){
                item.item_id = bahan.id
                item.jumlah = 1
                item.nama_item = bahan.nama_item
                item.satuan = bahan.satuan.satuan
            },
            submitForm(){
                axios.post(window.location.origin + '/kelola-stock/item-keluar', this.form)
                .then(response => {
                    console.log(response.data)
                    this.$emit("closePanel", {});
                })
                .catch(error => {
                    if (error.response.status == 422){
                        console.log(error.response.data.errors)
                        this.errors = error.response.data.errors
                    }
                })
            }
        },
        watch: {
            'form.list_item': {
            handler (newValue, oldValue) {
                newValue.forEach((item) => {
                    item.total_harga_beli = item.jumlah * item.harga_beli
                })
            },
            deep: true
            }
        },
    }
</script>
