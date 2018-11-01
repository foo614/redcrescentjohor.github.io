<template>
    <div>
        <v-navigation-drawer :clipped="$vuetify.breakpoint.smAndUp" absolute right v-model="drawer" temporary>
            <v-list dense>
                <template v-for="item in items">
                    <v-list-group
                        v-if="item.children"
                        v-model="item.model"
                        :key="item.text"
                        :prepend-icon="item.icon"
                    >
                        <v-list-tile slot="activator">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                        v-for="(child, i) in item.children"
                        :key="i"
                        exact
                        :to="child.link"
                        >
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
            <router-link to="/"><v-img src="/img/64x64.png" height="38px" width="38px"></v-img></router-link>
            <router-link to="/" style="font-weight: 500; font-size: 18px; text-decoration:none; color:black"><v-toolbar-title>Red Crescent Johor</v-toolbar-title></router-link>
            <v-spacer></v-spacer>
            <v-btn v-show="!mutableAuth" flat href="/social/login" >Sign In</v-btn>
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
                    <v-tab to="/course_registration">
                    Get Trained
                    </v-tab>
                    <v-tab href="#tab-1">
                    Fundraise
                    </v-tab>
                </v-tabs>   
                <!-- <v-btn flat to="/" class="test">Home</v-btn>
                <v-menu open-on-hover offset-y>
                    <v-btn flat to="/news-stories" slot="activator">Posts</v-btn>
                    <v-list>
                        <v-list-tile>
                            <v-list-tile-title>News</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-title>Stories</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile>
                            <v-list-tile-title>Memories</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>
                <v-btn flat to="/course_registration">Get Trained</v-btn>
                <v-btn flat>Donate</v-btn>
                <v-btn flat>Fundraise</v-btn> -->
            </v-toolbar-items>
        </v-toolbar>
    </div>
</template>

<script>
export default {
    data: () => ({
        drawer: null,
        items: [{
                    icon: "home",
                    text: "Home",
                    link: "/"
                },
                {
                    icon: "events",
                    text: "Posts",
                    link: "/posts",
                },
                {
                    icon: "accessibility_new",
                    text: "Get Trained",
                    link: "/courses"
                },
                {
                    icon: "payment",
                    text: "Donation",
                    link: "#"
                },
                {
                    icon: "local_library",
                    text: "Fundraise",
                    link: "#"
                },
            ],
        }),
    }
</script>
<style>

</style>
