<template>
  <div :id="'reply-'+id" class="card mt-4">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a>
          said {{ data.created_at }} ...
        </h5>

        <div v-if="signedIn">
          <favorite :reply="data"></favorite>
        </div>
      </div>
    </div>

    <div class="panel-body m-2">
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" v-model="body"></textarea>
        </div>

        <button class="btn btn-xs btn-primary" @click="update">Update</button>
        <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
      </div>

      <div v-else v-text="body"></div>
    </div>

    <!-- @can ('update', $reply)-->
    <div class="panel-footer level border-top my-3" v-if="canUpdate">
      <button class="btn btn-sm m-1" @click="editing = true">Edit</button>
      <button class="btn btn-sm m-1 btn-danger" @click="destroy">Delete</button>
    </div>
    <!-- @endcan-->
  </div>
</template>

<script>
import Favorite from "./Favorite.vue";

export default {
  props: ["data"],

  components: { Favorite },

  data() {
    return {
      editing: false,
      id: this.data.id,
      body: this.data.body,
    };
  },

  computed: {
    signedIn() {
      return window.App.signedIn;
    },

    canUpdate() {
      return this.authorize((user) => this.data.user_id == window.App.user.id);
      //return this.data.user_id == window.App.user.id;
    },
  },

  methods: {
    update() {
      axios.patch("/replies/" + this.data.id, {
        body: this.body,
      });

      this.editing = false;

      flash("Updated");
    },

    destroy() {
      axios.delete("/replies/" + this.data.id);

      this.$emit("deleted", this.data.id);
      // $(this.$el).fadeOut(300, () => {
      //   flash("Your reply has been deleted");
      // });
    },
  },
};
</script>