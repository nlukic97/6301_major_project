<template>
    <div class="d-flex">
        <div style="width:50%">
            <div id="my-div-2"></div>
            <div id="my-div"></div>
        </div>
        <div style="width:50%">
            <!-- So iframe is where the DOM output of the js code will be displayed-->
            <button id="exe-btn">Execute</button>
            <iframe frameborder="1" id="i-frame" style="width: 100%;height:40vh;"></iframe>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TextEditorComponent",
        data:function(){
            return{
                // name:'E'
            }
        },
        /** All the methods are defined in resources/js/codeMirror/ ,
         * and imported into views\class.blade.php
         * Importing them here would be the best thing */
        methods:{

        },
        mounted(){
            console.log('e')
        }
    }
</script>

<style scoped>

</style>
