<script>
export default {
  data: () => ({
    apiResponse: null,
    isOpen:false,
    currentUser: {},
  }),

  created() {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      const url = 'http://localhost:8000/';
      this.apiResponse = await (await fetch(url)).json();
    },
    openDetails(user) {
      this.currentUser = user;
      this.isOpen = true;
    },
    closeDetails() {
      if(this.isOpen) this.isOpen = false;
    }
  }
}
</script>

<template>
<div>
    <div v-if="!apiResponse" class="text-center mt-6">
        <svg aria-hidden="true" class="inline w-14 h-14 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
    </div>

    <div class="p-5" v-if="apiResponse">
        <p class="text-4xl mb-3 font-semibold">Users Weather Forecast</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 lg:gap-8">
            <div v-for="user in apiResponse.users" class="p-4 rounded-md flex items-center border shadow cursor-pointer hover:shadow-xl" @click="openDetails(user)">
                <div>
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12" :src="user.weather.icon" />
                        <small>{{ user.weather.forecast }}</small>
                    </div>
                    <div>
                        <div class="text-xl font-medium text-black">{{ user.name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="isOpen" class="absolute inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50">
      <div class="w-4/12 p-6 bg-white rounded-md shadow-xl" v-click-away="closeDetails">
        <div class="flex items-center justify-between">
          <h3 class="text-2xl">{{ currentUser.name }}</h3>
          <svg
            @click="closeDetails"
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8 text-red-900 cursor-pointer"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <div class="mt-4">
          <div class="flex-shrink-0">
              <img class="h-12 w-12" :src="currentUser.weather.icon" />
              <p class="mb-3">{{ currentUser.weather.forecast }}</p>
              <hr />
              <div class="grid grid-cols-2 mb-3">
                <p class="mt-2">Humidity : <span class="font-semibold">{{ currentUser.weather.humidity }}</span></p>
                <p class="mt-2">Temperature : <span class="font-semibold">{{ currentUser.weather.temperature }}</span></p>
                <p class="mt-2">Pressure : <span class="font-semibold">{{ currentUser.weather.pressure }}</span></p>
                <p class="mt-2">Wind : <span class="font-semibold">{{ currentUser.weather.windSpeed + ", " + currentUser.weather.windDirection }}</span></p>
              </div>
              <hr />
              <p class="mt-3">Summary : <span class="font-semibold capitalize">{{ currentUser.weather.forecastDescription }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>