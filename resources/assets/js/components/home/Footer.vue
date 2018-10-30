<template>
    <v-footer height="32px">
        <div class="container alpha-footer fluid">
            <div class="layout blue-grey darken-2 fill-height row wrap">
                <div class="flex pa-5 blue-grey darken-3 xs12 sm12 md3">
                    <h5 class="title pb-3">Red Crescent Johor</h5>
                    <ul>
                        <li><a href="#/services" class="layout align-center"><i aria-hidden="true" class="icon mr-3 theme--dark material-icons"></i>
                                <span class="subheading">GET TRAINED</span></a></li>
                        <li><a href="#/projects" class="layout align-center"><i aria-hidden="true" class="icon mr-3 theme--dark material-icons"></i>
                                <span class="subheading">DONATE</span></a></li>
                        <li><a href="#/team" class="layout align-center"><i aria-hidden="true" class="icon mr-3 theme--dark material-icons"></i>
                                <span class="subheading">FUNDRAISE</span></a></li>
                    </ul>
                </div>
                <div class="flex pa-5 xs12 sm12 md4">
                    <h5 class="title pb-3">Get connected with us! <i class="fab fa-facebook-square"></i></h5>
                    <div id="fb-root"></div>
                    <div class="fb-page" style="width:100%;" data-href="https://www.facebook.com/redcrescentjb/" data-tabs=""
                        data-height="auto" data-small-header="false" data-adapt-container-width="true"
                        data-hide-cover="false" data-show-facepile="false">
                        <blockquote cite="https://www.facebook.com/redcrescentjb/" class="fb-xfbml-parse-ignore"><a
                                href="https://www.facebook.com/redcrescentjb/">Malaysian Red Crescent Johor
                                Bahru Chapter</a></blockquote>
                    </div>
                </div>
                <div class="flex pa-5 xs12 sm12 md5">
                    <h5 class="title pb-3">Our location</h5>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.4620691797104!2d103.70036595060012!3d1.4939753615055744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da72c100c3ff15%3A0x3a2d77a6de21cac1!2sMalaysian+Red+Crescent!5e0!3m2!1sen!2smy!4v1539076729166"
                        width="100%" height="auto" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                <div class="flex xs12">
                    <div class="blue-grey darken-4 text-xs-center card card--flat card--tile theme--dark" style="height: auto;">
                        <div class="card__text"><span>
                            © 2018
                            Red Crescent Johor
                            —
                            All Rights reserved.
                        </span></div>
                    </div>
                </div>
            </div>
        </div>
    </v-footer>
</template>

<script>
export default {
    mounted() {
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src =
                'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.1&appId=1068803136585486&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }
}
</script>

<style>

</style>
