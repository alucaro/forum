<template>
  <button type="submit" :class="classes" @click="toggle">
    <span class="glyphicon glyphicon-heart-empty"></span>
    <span v-text="count"></span>
  </button>
</template>

<script>
export default {
  props: ["reply"],

  //change isFavorited by active and favoritedCount by count
  data() {
    return {
      count: this.reply.favoritesCount,
      active: this.reply.isFavorited,
    };
  },

  computed: {
    classes() {
      return ["btn", this.active ? "btn-primary" : "btn-default"];
    },

    endpoint() {
      return "/replies/" + this.reply.id + "/favorites";
    },
  },

  methods: {
    toggle() {
      this.active ? this.destroy() : this.create();
      // if (this.isFavorited) {
      //   this.destroy();
      // } else {
      //   this.create();
      // }
    },

    create() {
      axios.post(this.endpoint);
      this.active = true;
      this.count++;
    },

    destroy() {
      axios.delete(this.endpoint); //create end point
      this.active = false;
      this.count--;
    },
  },
};
</script>
