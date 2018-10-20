<template>
    <div>
        <v-navigation-drawer :clipped="$vuetify.breakpoint.mdAndUp" app right v-model="drawer" temporary width=250>
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
        <v-toolbar :clipped-right="$vuetify.breakpoint.mdAndUp" app fixed color="white" height="78">
            <router-link to="/"><img src="/img/64x64.png" height="38px" width="38px"></router-link>
            <router-link to="/" style="font-weight: 500; font-size: 18px; text-decoration:none; color:black"><v-toolbar-title>Red Crescent Johor</v-toolbar-title></router-link>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat to="/">Home</v-btn>
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
                <v-btn flat>Fundraise</v-btn>
            </v-toolbar-items>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
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