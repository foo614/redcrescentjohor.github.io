<template>
  <div>
    <md-table v-model="items" md-sort="name" md-sort-order="asc" md-card @md-selected="onSelect" md-fixed-header>
      <md-table-toolbar>
        <h1 class="md-title">Users</h1>
        <v-btn primary>Material Button</v-btn>
        <md-field md-clearable class="md-toolbar-section-end">
          <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
        </md-field>
      </md-table-toolbar>
      <md-table-empty-state
        md-label="No users found"
        :md-description="`No user found for this '${search}' query. Try a different search term or create a new user.`">
        <md-button class="md-primary md-raised">Create New User</md-button>
      </md-table-empty-state>
      <md-table-toolbar slot="md-table-alternate-header" slot-scope="{ count }">
        <div class="md-toolbar-section-start">{{ getAlternateLabel(count) }}</div>

        <div class="md-toolbar-section-end">
          <md-button class="md-icon-button">
            <md-icon>delete</md-icon>
          </md-button>
        </div>
      </md-table-toolbar>
      <md-table-row slot="md-table-row" slot-scope="{ item }" md-selectable="multiple" md-auto-select>
        <md-table-cell md-label="Name" md-sort-by="name">{{ item.name }}</md-table-cell>
        <md-table-cell md-label="Email" md-sort-by="email">{{ item.email }}</md-table-cell>
        <md-table-cell md-label="Contact" md-sort-by="contact">{{ item.contact }}</md-table-cell>
        <md-table-cell md-label="Roles" md-sort-by="roles">
          <md-chip class="md-accent" v-for="role in item.roles" :key="role.id" md-static>{{ role.name }}</md-chip>
        </md-table-cell>
      </md-table-row>
    </md-table>

    <p>Selected:</p>
    {{ selected }}
  </div>
</template>

<script>
import axios from 'axios';

const toLower = text => {
  return text.toString().toLowerCase()
}

const searchByName = (items, term) => {
  if (term) {
    return items.filter(
      item => toLower(item.name).includes(toLower(term))
    );
  }
  return items
}
  export default {
    data: () => ({
      search: null,
      selected: [],
      items:[],
      data:[],
    }),
    created(){
      this.fetchPosts();
    },
    methods: {
      fetchPosts: function (){
        axios.get('api/users').then(res =>{
          this.data = res.data;
          this.items = this.data;
        })
      },
      onSelect (items) {
        this.selected = items
      },
      getAlternateLabel (count) {
        let plural = ''

        if (count > 1) {
          plural = 's'
        }

        return `${count} user${plural} selected`
      },
      searchOnTable () {
        this.items = searchByName(this.data, this.search);
      }
    }
  }
</script>

<style lang="scss" scoped>
  .md-table + .md-table {
    margin-top: 16px
  }
  .md-field {
    max-width: 300px;
  }
</style>