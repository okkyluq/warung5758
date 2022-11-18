<template>
    <div class="row">
        <div class="col-md-12">
							

            <div class="panel panel-success" style="border-bottom: none;">
                <div class="panel-heading" style="border-top-right-radius: 0; border-top-left-radius:0;">
                    <h6 class="panel-title">Buat Menu Baru<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                    <div class="heading-elements">
                        <button v-on:click.prevent="closePanel" type="button" class="btn btn-danger"><i class="icon-circle-left2 position-left"></i> Batal</button>
                        <button v-on:click.prevent="submitForm" type="button" class="btn btn-primary"><i class="icon-floppy-disk position-left"></i> Simpan</button>
                    </div>
                </div>

                <div class="panel-body">
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Kode Menu :</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" placeholder="Kode Menu" v-model="form.kode_menu" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama Menu :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" placeholder="Masukan Nama Menu" v-model="form.nama_menu">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Satuan Menu :</label>
                            <div class="col-sm-3">
                             <v-select label="satuan" placeholder="Silahkan Pilih Satuan" :options="options_satuan" @search="onSearchSatuan" v-model="form.satuan">
                                <template slot="no-options">
                                    Silahkan Masukan Nama Satuan
                                </template>
                            </v-select>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Jumlah Hasil Produksi :</label>
                            <div class="col-sm-2">
                                <vue-numeric 
                                    class="form-control text-right qty"
                                    placeholder="Jumlah"
                                    v-bind:minus="false" 
                                    separator="," 
                                    v-model="form.qty">
                                </vue-numeric>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Total Cost :</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" placeholder="Total Cost" v-model="form.total_cost" readonly>
                            </div>
                        </div>
                        <div class="alert alert-danger alert-styled-right alert-bordered" v-if="errors.list_item">
                            <span class="text-semibold">{{ errors.list_item[0] }}</span></a>
                        </div>
                        <div class="table">
							<table class="table table-xxs">
								<thead>
									<tr>
										<th class="col-lg-4 text-center">Nama Bahan</th>
										<th class="col-lg-1 text-center">Jumlah</th>
										<th class="col-lg-1 text-center">Satuan</th>
										<th class="col-lg-2 text-center">Cost Standar</th>
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
                                            <v-select label="nama_item" :options="options" @search="onSearch" @input="produk => setSelected(item, produk)" v-model="item.nama_item" placeholder="Silahkan Pilih Item">
                                                <template slot="no-options">
                                                    Silahkan Masukan Item
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
                                                v-model="item.standar_cost">
                                            </vue-numeric>
                                            <div class="label-block" v-if="errors['list_item.'+i+'.standar_cost']">
                                                <span class="help-block text-danger">{{errors['list_item.'+i+'.standar_cost'][0]}}</span>
                                            </div>
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
                            <button type="button" class="btn btn-link btn-xs text-info" v-on:click="add_line"><i class="icon-plus-circle2 position-left text-info"></i> Tambah Bahan</button>
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
                options_satuan: [],
                options: [],
                form: {
                    kode_menu: '',
                    nama_menu: '',
                    qty: '',
                    total_cost: '',
                    list_item: []
                }
            }
        },
        mounted(){
            this.getKodeMenu()
        },
        methods: {
            getKodeMenu: function(){
                axios.get(window.location.origin + '/getkodemenu')
                .then(response => {
                    this.form.kode_menu = response.data
                })
                .catch(error => {
                    alert('gagal mengambil kode menu')
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
            onSearchSatuan(search, loading) {
                if(search.length) {
                    loading(true);
                    this.searchSatuan(loading, search, this);
                }
            },
            searchSatuan:(loading, search, vm) => {
                fetch(
                    window.location.origin + `/getsatuan?q=${escape(search)}`
                ).then(res => {
                    res.json().then(json => (vm.options_satuan = json.items));
                    loading(false);
                });
            },
            add_line() {
                this.form.list_item.push({
                    item_id: '',
                    nama_item: '',
                    jumlah: '',
                    satuan: '',
                    standar_cost: '',
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
            setSelected(item, produk){
                item.item_id = produk.id
                item.jumlah = 1
                item.nama_item = produk.nama_item
                item.satuan = produk.satuan.satuan
            },
            submitForm(){
                axios.post(window.location.origin + '/transaksi/produk-masuk', this.form)
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
                    item.total_standar_cost = item.jumlah * item.standar_cost
                })
            },
            deep: true
            }
        },
    }
</script>
