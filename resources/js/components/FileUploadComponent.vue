<style scoped>
    div.form-group {
        margin-top: 10px;
    }
</style>

<template>
    <div class="form-group">
        <label for="picture">上传一张图片</label>
        <input type="file" class="form-control-file" id="picture" ref="picture" v-on:change="uploadFile"/>
    </div>
</template>

<script>
    export default {
        methods: {
            uploadFile() {
                let formData = new FormData();
                formData.append('picture', this.$refs.picture.files[0]);
                axios.post(
                    '/request/upload_file',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function (response) {
                    console.log(response);
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>