<template>
  <div>
    <title-bar :title-stack="titleStack" />
    <hero-bar>
      회원목록
      <nuxt-link slot="right" to="/admin/users/edit" class="button">
        <b-icon
          icon="account-plus-outline"
          custom-size="default"
          style="margin-right: 5px;"
        />
        회원추가
      </nuxt-link>
    </hero-bar>
    <section class="section is-main-section">
      <notification class="is-info">
        <div>
          <b-icon icon="buffer" custom-size="default" />
          <b>Sorted and paginated table.</b>&nbsp;Based on Buefy's table.
        </div>
      </notification>

      <card-component
        class="has-table has-mobile-sort-spaced"
        title="Clients"
        icon="account-multiple"
      >
        <clients-table-sample
          :data-url="`${this.$router.options.base}data-sources/clients.json`"
          :checkable="true"
        />
      </card-component>

      <hr>

      <notification class="is-info">
        <div>
          <b-icon icon="buffer" custom-size="default" />
          <b>Tightly wrapped</b> &mdash; table header becomes card header
        </div>
      </notification>

      <card-component class="has-table has-mobile-sort-spaced">
        <clients-table-sample
          :data-url="`${this.$router.options.base}data-sources/clients.json`"
          :checkable="true"
        />
      </card-component>

      <hr>

      <notification class="is-info">
        <div>
          <b-icon icon="buffer" custom-size="default" />
          <b>Empty table.</b> When there's nothing to show
        </div>
      </notification>

      <card-component class="has-table">
        <clients-table-sample />
      </card-component>
    </section>
    {{ level }}
  </div>
</template>

<script>
import Notification from '@/components/Notification'
import ClientsTableSample from '@/components/ClientsTableSample'
import CardComponent from '@/components/CardComponent'
import TitleBar from '@/components/TitleBar'
import HeroBar from '@/components/HeroBar'
export default {
  name: 'Users',
  components: {
    HeroBar,
    TitleBar,
    CardComponent,
    ClientsTableSample,
    Notification
  },
  computed: {
    titleStack () {
      return ['Admin', 'Users']
    },
    userList () {
      return this.$store.state.users.list
    },
    level () {
      return process.env.level
    }
  },
  mounted () {
    this.$store.dispatch("users/getUserList", {
      page: this.$route.params.page ? this.$route.params.page : 1,
      limit: 15,
      auth: 'user'
    })
  },
  head () {
    return {
      title: '회원목록 — 관리자페이지'
    }
  }
}
</script>
