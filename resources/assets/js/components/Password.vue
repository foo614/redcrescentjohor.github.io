<template>
<v-form>
    <v-text-field prepend-icon="person" name="login" label="Login" type="text"></v-text-field>
    <v-text-field 
    prepend-icon="lock" 
    :type="show1 ? 'text' : 'password'" 
    @click:append="show1 = !show1" 
    :append-icon="show1 ? 'visibility_off' : 'visibility'" 
    name="password" 
    label="Password" 
    id="password" >
    </v-text-field>
</v-form>
</template>

<script>
export default {
    data(){
        return{
            show1: false
        }
    }
}
</script>

<style>

</style>
