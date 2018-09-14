<template>
    <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--6-col-desktop">
        <i class="mdl-textfield__icon material-icons">insert_photo</i>
        <div style="position:relative; display: -webkit-inline-box;">
            <label class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" id="tootip-upload-image" for="file"><i class="material-icons">cloud_upload</i></label>
            <input type="file" id="file" ref="file" name="avatar" v-on:change="handleFile">
            <div style="margin-left:10px;">
                <img :src="imgPath" class="preview" v-bind:ref="'preview'" style="width:180px; height:160px;" v-show="isInsert == true || imgPath!=''"/>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['imgPath'],
    mounted () {
        // Do something useful with the data in the template
        console.dir(this.imgPath)
    },
    data() {
        return {
            file: '',
            isInsert: false
        }
    },
  methods: {
    handleFile(){
        this.file = this.$refs.file.files;
        this.getImagePreview();
    },
    getImagePreview(){
        if ( /\.(jpe?g|png|gif)$/i.test( this.file[0].name ) ) {
            let reader = new FileReader();
            reader.addEventListener("load", function(){
                this.$refs['preview'].src = reader.result;
            }.bind(this), false);
            this.isInsert = true;
            reader.readAsDataURL( this.file[0] );
        }
    },
  }
};
</script>
<style>
.dropzone{
    overflow-x: auto;
    border-style: dashed;
    border-color: #2c003a;
    border-width: 2px;
    color: #462a51;
    white-space: nowrap;
    height: 180px;
}

input[type="file"]{
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}
</style>
