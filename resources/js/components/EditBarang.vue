<template>
    <div>
        <form  method="POST" enctype="multipart/form-data" @submit.prevent="sendData" action="window.location.origin">
        <div class="form-group">
            <div class="row">
                <div class="col-md-3" :class="errors.sku ? 'has-error' : ''">
                    <label class="text-bold">SKU :</label>
                    <input type="text" class="form-control text-bold" placeholder="SKU Produk" id="sku" name="sku" style="text-transform:uppercase" v-model="form.sku">
                    <div class="label-block" v-if="errors.sku">
                        <span class="help-block">{{ errors.sku[0] }}</span>
                    </div>	
                </div>
                <div class="col-md-3" :class="errors.barcode ? 'has-error' : ''">
                    <label class="text-bold">Barcode Barang :</label>
                    <input type="text" class="form-control text-bold" placeholder="Barcode Produk" id="barcode" name="barcode" v-model="form.barcode">
                    <div class="label-block" v-if="errors.barcode">
                        <span class="help-block">{{ errors.barcode[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6" :class="errors.nama_produk ? 'has-error' : ''">
                    <label class="text-bold">Nama Produk :</label>
                    <input type="text" class="form-control text-bold" placeholder="Nama Produk" name="nama_produk" id="nama_produk" v-model="form.nama_produk">
                    <div class="label-block" v-if="errors.nama_produk">
                        <span class="help-block">{{ errors.nama_produk[0] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-3" :class="errors.kategori_produk ? 'has-error' : ''">
                    <label class="text-bold">Kategori Produk :</label>
                    <v-select label="kategori" placeholder="Silahkan Pilih Kategori" :options="options_kategori" @search="onSearchKategori" v-model="form.kategori">
                        <template slot="no-options">
                            Silahkan Masukan Nama Kategori
                        </template>
                    </v-select>
                    <div class="label-block" v-if="errors.kategori_produk">
                        <span class="help-block">{{ errors.kategori_produk[0] }}</span>
                    </div>
                </div>
                <div class="col-md-3" :class="errors.satuan_produk ? 'has-error' : ''">
                    <label class="text-bold">Satuan Produk :</label>
                    <v-select label="satuan" placeholder="Silahkan Pilih Satuan" :options="options_satuan" @search="onSearchSatuan" v-model="form.satuan">
                        <template slot="no-options">
                            Silahkan Masukan Nama Satuan
                        </template>
                    </v-select>
                    <div class="label-block" v-if="errors.satuan_produk">
                        <span class="help-block">{{ errors.satuan_produk[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-3" :class="errors.stock_warning ? 'has-error' : ''">
                    <label class="text-bold">Stok Warning :</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-bold" placeholder="Stock Warning" name="stock_warning" id="stock_warning" v-model="form.stock_warning" @keypress="isNumber($event)">
                        <span class="input-group-addon">{{ !form.satuan ? 'NOT SET' : form.satuan.satuan }}</span>
                    </div>
                    <div class="label-block" v-if="errors.stock_warning">
                        <span class="help-block">{{ errors.stock_warning[0] }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="text-bold">Opsi Produk :</label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="control-primary" name="opsi_produk" id="opsi_produk" v-model="form.opsi_produk">Produk Dijual
                        </label>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <label class="text-bold">Gambar Barang :</label>
                    <picture-input 
                        ref="pictureInput"
                        width="300" 
                        height="300" 
                        margin="10" 
                        accept="image/jpeg,image/png" 
                        size="20" 
                        button-class="btn"
                        :custom-strings="{
                            upload: '<h1>Bummer!</h1>',
                            drag: 'Pilih Gambar!'
                        }"
                        @change="setImage"
                        :prefill="img_url">
                    </picture-input>
                    <div class="label-block" v-if="errors.gambar">
                        <span class="help-block text-danger text-bold">{{ errors.gambar[0] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <legend class="text-bold">Jenis Produk</legend>
        <div class="form-group">
            <div class="row">
                <div class="col-md-8">
                    <input type="hidden" id="jenis_produk" name="jenis_produk" v-model="form.jenis_produk">
                    <button type="button" class="btn btn-float btn-float-lg" v-bind:class="form.jenis_produk === 'tunggal' ? 'btn-success' : 'btn-default'" v-on:click="form.jenis_produk = 'tunggal'; errors ? errors.list_bahan = [] : ''"><i class="icon-file-empty2"></i> <span>Produk Tunggal</span></button>
                    <button type="button" class="btn btn-float btn-float-lg" v-bind:class="form.jenis_produk === 'komposit' ? 'btn-success' : 'btn-default'" v-on:click="form.jenis_produk = 'komposit'; errors ? errors.list_bahan = [] : ''"><i class="icon-file-text3"></i> <span>Produk Komposit</span></button>
                </div>
            </div>
        </div>
        <div class="alert alert-danger alert-styled-right alert-bordered" v-if="errors.list_bahan">
            <span class="text-semibold">{{ errors.list_bahan[0] }}</span>
        </div>
        <table class="table table-framed table-xs" id="tabel-bahan-resep" v-if="form.jenis_produk === 'komposit'">
            <thead class="bg-success">
                <tr>
                    <th class="text-center text-bold">Bahan</th>
                    <th width="150" class="text-center">Jumlah</th>
                    <th width="90">Satuan</th>
                    <th class="text-center" width="20"><i class="icon-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                <template v-if="!form.list_bahan">
                <tr>
                    <td class="text-center text-bold" colspan="4">Bahan Belum ada</td>
                </tr>
                </template>
                <template v-else>
                <tr v-for="(item, i) in form.list_bahan">
                    <td>
                        <input type="hidden" id="bahan_id" name="bahan_id" v-model="item.bahan_id">
                        <v-select label="nama_produk" :options="options" @search="onSearch" @input="produk => setSelected(item, produk)" v-model="item.nama_bahan" placeholder="Silahkan Pilih Bahan">
                            <template slot="no-options">
                                Silahkan Masukan Nama Bahan
                            </template>
                        </v-select>
                        <div class="label-block" v-if="errors['list_bahan.'+i+'.bahan_id']">
                            <span class="help-block text-danger text-bold">{{errors['list_bahan.'+i+'.bahan_id'][0]}}</span>
                        </div>
                    </td>
                    <td class="text-bold">
                        <input type="text" class="form-control text-right qty" v-model="item.qty" @keypress="isNumber($event)">
                        <div class="label-block" v-if="errors['list_bahan.'+i+'.qty']">
                            <span class="help-block text-danger">{{errors['list_bahan.'+i+'.qty'][0]}}</span>
                        </div>
                    </td>
                    <td class="text-center text-bold">
                        {{item.satuan ? item.satuan : '-'}}
                    </td>
                    <td class="text-center text-bold">
                        <button type="button" class="btn btn-link" v-on:click="remove_line(i)"><i class="text-danger icon-trash"></i></button>
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
        
        <div class="form-group has-feedback" style="margin-top: 10px;" v-if="form.jenis_produk === 'komposit'">
            <button type="button" class="btn btn-link btn-xs text-info" v-on:click="add_line"><i class="icon-plus-circle2 position-left text-info"></i> Tambah Bahan Baku</button>
        </div>

        <div class="form-group" style="margin-top: 10px;">
            <div class="row pull-right">
                <div class="col-md-12">
                    <a :href="back" class="btn btn-sm bg-danger btn-labeled text-bold"><b><i class="icon-circle-left2"></i></b> Batal & Kembali</a>
                    <button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Update Data</button>
                </div>
            </div>
        </div>


        </form>
    </div>
</template>
<style>
    .vs__dropdown-toggle {
        height: 36px;
    }

    .table-xs>tbody>tr>td{
        padding: 8px 20px;
    }

</style>
<script>
    import 'vue-select/dist/vue-select.css';
    import vSelect from 'vue-select'
    import PictureInput from 'vue-picture-input'
    import axios from 'axios';

    export default {
        props: ['edit'],
        components: {vSelect, PictureInput},
        data(){
            return {
                errors:'',
                form : {
                    sku: '',
                    barcode: '',
                    nama_produk: '',
                    opsi_produk: '',
                    jenis_produk: "tunggal",
                    gambar: '',
                    list_bahan:[],
                },
                options_kategori:[],
                options_satuan:[],
                list_bahan: [],
                options: [],
                value: '',
                back: window.location.origin + '/data-master/produk',
                img_url: '',
                url:'',
            }
        },
        mounted() { 
            this.form = this.edit
            this.img_url = this.edit.gambar
            this.url = window.location.origin + '/data-master/produk/' + this.edit.id
        },
        methods: {
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            onSearchKategori(search, loading) {
                if(search.length) {
                    loading(true);
                    this.searchKategori(loading, search, this);
                }
            },
            searchKategori:(loading, search, vm) => {
                fetch(
                    window.location.origin + `/getkategori?q=${escape(search)}`
                ).then(res => {
                    res.json().then(json => (vm.options_kategori = json.items));
                    loading(false);
                });
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
            setImage () {
                if (this.$refs.pictureInput.file) {
                    console.log(this.$refs.pictureInput.file)
                    this.form.gambar = this.$refs.pictureInput.file;
                } else {
                    this.form.gambar = "";
                }
            },
            add_line() {
                this.form.list_bahan.push({
                    bahan_id: '',
                    nama_bahan: '',
                    satuan: '',
                    qty: ''
                });
            },
            remove_line(id){
                this.form.list_bahan.splice(id,1);
            },
            onSearch(search, loading) {
                if(search.length) {
                    loading(true);
                    this.search(loading, search, this);
                }
            },
            search:(loading, search, vm) => {
                fetch(
                    window.location.origin + `/getproduk?q=${escape(search)}`
                ).then(res => {
                    res.json().then(json => (vm.options = json.items));
                    loading(false);
                });
            },
            setSelected(item, produk){
                item.bahan_id = produk.id
                item.nama_bahan = produk.nama_produk
                item.satuan = produk.satuan.satuan
            },
            sendData(){
                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data'
                    }
                }

                let data = new FormData();
                data.append('sku', this.form.sku);
                data.append('barcode', this.form.barcode);
                data.append('nama_produk', this.form.nama_produk);
                data.append('kategori_produk', this.form.kategori ? this.form.kategori.id : '');
                data.append('satuan_produk', this.form.satuan ? this.form.satuan.id : '');
                data.append('stock_warning', this.form.stock_warning);
                data.append('opsi_produk', this.form.opsi_produk);
                data.append('jenis_produk', this.form.jenis_produk);
                data.append('gambar', this.form.gambar == "" ? null : this.form.gambar);
                data.append('list_bahan', JSON.stringify(this.form.list_bahan));
                data.append('_method', 'patch');

                axios.post(this.url, data, config)
                .then(response => {
                    window.location.replace(window.location.origin + '/data-master/produk/');
                    // console.log(response)
                })
                .catch(error => {
                    if (error.response.status == 422){
                        console.log(error.response.data.errors)
                        this.errors = error.response.data.errors
                    }
                })
            }
        }
    }
</script>
