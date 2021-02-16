new Vue({
    el: '#app',
    data() {
        return {
            quotes:[]
        }
    },
    created() {
        this.getQuotes()
    },
    methods: {
        getQuotes(){
            axios.get(baseUrl+'/quotes').then(response=>{
                if(response.data.success==true){
                    this.quotes = response.data.datas
                }
            }).catch(err=>{
                console.log(err);
                alert('Found SOmething error on server');
            })
        },
        refresh(){
            this.getQuotes();
        }
    },
})

Vue.component('comp-quote', {
    props: ['quote'],
    template: '<li>{{ quote }}</li>'
  })