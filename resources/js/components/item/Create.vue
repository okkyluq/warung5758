<template>
    <div>
        <form  method="POST" enctype="multipart/form-data" @submit.prevent="sendData" action="window.location.origin">
        <div class="form-group">
            <div class="row">
                <div class="col-md-3" :class="errors.sku ? 'has-error' : ''">
                    <label class="text-bold">SKU :</label>
                    <input type="text" class="form-control text-bold" placeholder="SKU Item" id="sku" name="sku" style="text-transform:uppercase" v-model="form.sku">
                    <div class="label-block" v-if="errors.sku">
                        <span class="help-block">{{ errors.sku[0] }}</span>
                    </div>	
                </div>
                <div class="col-md-3" :class="errors.barcode ? 'has-error' : ''">
                    <label class="text-bold">Barcode Barang :</label>
                    <input type="text" class="form-control text-bold" placeholder="Barcode Item" id="barcode" name="barcode" v-model="form.barcode">
                    <div class="label-block" v-if="errors.barcode">
                        <span class="help-block">{{ errors.barcode[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6" :class="errors.nama_item ? 'has-error' : ''">
                    <label class="text-bold">Nama Item :</label>
                    <input type="text" class="form-control text-bold" placeholder="Nama Item" name="nama_item" id="nama_item" v-model="form.nama_item">
                    <div class="label-block" v-if="errors.nama_item">
                        <span class="help-block">{{ errors.nama_item[0] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-3" :class="errors.kategori_item ? 'has-error' : ''">
                    <label class="text-bold">Kategori Item :</label>
                    <v-select label="kategori" placeholder="Silahkan Pilih Kategori" :options="options_kategori" @search="onSearchKategori" v-model="form.kategori">
                        <template slot="no-options">
                            Silahkan Masukan Nama Kategori
                        </template>
                    </v-select>
                    <div class="label-block" v-if="errors.kategori_item">
                        <span class="help-block">{{ errors.kategori_item[0] }}</span>
                    </div>
                </div>
                <div class="col-md-3" :class="errors.satuan_item ? 'has-error' : ''">
                    <label class="text-bold">Satuan Item :</label>
                    <v-select label="satuan" placeholder="Silahkan Pilih Satuan" :options="options_satuan" @search="onSearchSatuan" v-model="form.satuan">
                        <template slot="no-options">
                            Silahkan Masukan Nama Satuan
                        </template>
                    </v-select>
                    <div class="label-block" v-if="errors.satuan_item">
                        <span class="help-block">{{ errors.satuan_item[0] }}</span>
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
                        @change="setImage">
                    </picture-input>
                    <div class="label-block" v-if="errors.gambar">
                        <span class="help-block text-danger text-bold">{{ errors.gambar[0] }}</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group" style="margin-top: 10px;">
            <div class="row pull-right">
                <div class="col-md-12">
                    <a :href="back" class="btn btn-sm bg-danger btn-labeled text-bold"><b><i class="icon-circle-left2"></i></b> Batal & Kembali</a>
                    <button type="submit" class="btn btn-sm bg-success btn-labeled text-bold"><b><i class="icon-floppy-disk"></i></b> Simpan Data</button>
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
        components: {vSelect, PictureInput},
        data(){
            return {
                errors:'',
                form : {
                    sku: '',
                    barcode: '',
                    nama_item: '',
                    gambar: '',
                    stock_warning: '',
                },
                options_kategori:[],
                options_satuan:[],
                options: [],
                value: '',
                back: window.location.origin + '/data-master/item',
            }
        },
        mounted() { 
            console.log(window.location.origin)
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
            sendData(){
                let config = {
                    header : {
                        'Content-Type' : 'multipart/form-data'
                    }
                }

                let data = new FormData();
                data.append('sku', this.form.sku);
                data.append('barcode', this.form.barcode);
                data.append('nama_item', this.form.nama_item);
                data.append('kategori_item', this.form.kategori ? this.form.kategori.id : '');
                data.append('satuan_item', this.form.satuan ? this.form.satuan.id : '');
                data.append('stock_warning', this.form.stock_warning);
                data.append('gambar', this.form.gambar);


                axios.post(window.location.origin + '/data-master/item/', data, config)
                .then(response => {
                    // console.log(response.data)
                    window.location.replace(window.location.origin + '/data-master/item');
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
