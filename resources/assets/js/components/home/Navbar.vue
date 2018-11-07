<template>
    <div>
        <v-navigation-drawer :clipped="$vuetify.breakpoint.smAndUp" absolute right v-model="drawer" temporary>
            <v-list dense>
                <template v-for="item in items">
                    <v-list-group v-if="item.children" v-model="item.model" :key="item.text" :prepend-icon="item.icon">
                        <v-list-tile slot="activator">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile v-for="(child, i) in item.children" :key="i" exact :to="child.link">
                            <v-list-tile-action v-if="child.icon">
                                <v-icon>{{ child.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ child.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list-group>
                    <v-list-tile v-else :key="item.text" :to="item.link">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar tabs>
            <router-link to="/">
                <v-img src="/img/64x64.png" height="38px" width="38px"></v-img>
            </router-link>
            <router-link to="/" style="font-weight: 500; font-size: 18px; text-decoration:none; color:black">
                <v-toolbar-title>Red Crescent Johor</v-toolbar-title>
            </router-link>
            <v-spacer></v-spacer>
            <v-menu offset-y v-model="showMenu" v-if="authCheck==1">
                <v-avatar size="36" v-if="mutableAuth.avatar" slot="activator">
                    <img :src="'/img/'+mutableAuth.avatar" alt="mutableAuth.avatar">
                </v-avatar>
                <v-avatar color="#757575" slot="activator" v-else>
                    <span class="white--text headline">{{mutableAuth.name | getFirstLetter}}</span>
                </v-avatar>
                <v-list style="min-width: 250px;">
                    <v-list-tile :to="{ name: 'profileHome', params: { id: mutableAuth.id}}">
                        <v-list-tile-avatar color="#757575">
                            <img :src="/img/+mutableAuth.avatar" :alt="mutableAuth.avatar" v-if="mutableAuth.avatar">
                            <span class="white--text headline" v-else>{{mutableAuth.name | getFirstLetter}}</span>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{mutableAuth.name}}</v-list-tile-title>
                            <v-list-tile-sub-title>{{mutableAuth.email}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                    <v-divider inset></v-divider>
                    <v-list-tile @click="logout">
                        <v-list-tile-action>
                            <v-icon style="transform: rotate(270deg);">save_alt</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>Logout</v-list-tile-title>
                        </v-list-tile-content>
                        <form style="display: hidden" action="/logout" method="POST" id="logout-form">
                            <input type="hidden" name="_token" :value="csrf_token" />
                        </form>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn flat href="/social/login" v-show="authCheck==0">Sign In</v-btn>
            <v-btn icon>
                <v-icon>search</v-icon>
            </v-btn>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-items slot="extension">
                <v-tabs color="transparent" show-arrows>
                    <v-tabs-slider color="#ca0000"></v-tabs-slider>
                    <v-tab to="/">
                        Home
                    </v-tab>
                    <v-tab to="/news-stories">
                        Posts
                    </v-tab>
                    <v-tab to="/course-registration">
                        Get Trained
                    </v-tab>
                    <v-tab to="/fundraisers-campaign">
                        Fundraisers @ Donation
                    </v-tab>
                </v-tabs>
            </v-toolbar-items>
        </v-toolbar>
    </div>
</template>

<script>
    export default {
        props: 
            ['auth', 'authCheck']
        ,
        data: () => ({
            showMenu: false,
            csrf_token: window.csrf_token,
            drawer: null,
            items: [{
                    icon: "home",
                    text: "Home",
                    link: "/"
                },
                {
                    icon: "events",
                    text: "Posts",
                    link: "/posts"
                },
                {
                    icon: "accessibility_new",
                    text: "Get Trained",
                    link: "/courses"
                },
                {
                    icon: "local_library",
                    text: "Fundraise",
                    link: "#"
                }
            ],
            mutableAuth:{}
        }),
        created() {
            this.mutableAuth = this.auth ? JSON.parse(this.auth) : "";
            window.user = [this.mutableAuth.name, this.mutableAuth.email];
        },
        methods: {
            logout() {
            document.getElementById("logout-form").submit();
            }
        },
        filters: {
            getFirstLetter: function(value) {
            if (!value) return "";
            return value
                .split(" ")
                .map(function(item) {
                return item[0];
                })
                .join("").slice(0,2);
            }
        }
    };
</script>
<style>
</style>