<template>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col-tablet mdl-cell--12-col-desktop">
            <i class="mdl-textfield__icon material-icons">attach_file</i>
            <div class="dropzone">
                <input type="file" id="files" ref="files" name="avatar" v-on:change="handleFiles()">
                <p v-if="files.length === 0">Drop your files here <br>or click to search</p>
                <div v-else v-for="(file, key) in files" :key="key" style="display:-webkit-inline-box;">
                    <div style="padding: 10px;">
                        <img class="preview" v-bind:ref="'preview'+parseInt(key)" style="width:180px; height:160px;"/>
                        <a class="mdl-badge mdl-badge--overlap" data-badge="x" v-on:click="removeFile(key)" style="font-size: 85px;"></a>
                    </div>
                    <div class="success-container" v-if="file.id > 0">
                        Success
                        <input type="hidden" :name="input_name" :value="file.id"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            files: []
        }
    },
    methods: {
        handleFiles(){
            let uploads= this.$refs.files.files;

            for(var i = 0; i < uploads.length; i++) {
                this.files.push(uploads[i]);
            }

            this.getImagePreview();
        },
        getImagePreview(){
            for( let i = 0; i < this.files.length; i++ ){
                if ( /\.(jpe?g|png|gif)$/i.test( this.files[i].name ) ) {
                    let reader = new FileReader();
                    reader.addEventListener("load", function(){
                        this.$refs['preview'+parseInt(i)][0].src = reader.result;
                    }.bind(this), false);
                    reader.readAsDataURL( this.files[i] );
                }else{
                    this.$nextTick(function(){
                        this.$refs['preview'+parseInt(i)][0].src = '/img/generic.png';
                    });
                }
            }
        },
        removeFile( key ){
            this.files.splice( key, 1 );
            this.getImagePreview();
        }
    }
}
</script>

<style scoped>
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
    opacity: 0;
    width: 100%;
    height: 180px;
    position: absolute;
    cursor: pointer;
}
.filezone {
    outline: 2px dashed grey;
    outline-offset: -10px;
    background: #ccc;
    color: dimgray;
    padding: 10px 10px;
    min-height: 200px;
    position: relative;
    cursor: pointer;
}
.filezone:hover {
    background: #c0c0c0;
}

.filezone p {
    font-size: 1.2em;
    text-align: center;
    padding: 50px 50px 50px 50px;
}
div.file-listing img{
    max-width: 90%;
}

div.file-listing{
    margin: auto;
    padding: 10px;
}

div.file-listing img{
    height: 100px;
}
div.success-container{
    text-align: center;
    color: green;
}

div.remove-container{
    text-align: center;
}

div.remove-container a{
    color: red;
    cursor: pointer;
}
</style>