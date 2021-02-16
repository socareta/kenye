<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            .container {
                width: 100%;
                margin: 0 auto;
                padding-top: 10%;
            }
            @media only screen and (min-width:645px){
                .container {
                    width: 500px;
                    margin: 0 auto;
                    padding-top: 10%;
                }
            }
            .btn:hover {
    background: #fff;
    color: grey;
    font-weight: 500;
}
.btn {
    border: 1px solid grey;
    padding: 5px 10px;
    font-size: 15px;
    border-radius: 5px;
    background: grey;
    text-decoration: none;
    color: #fff;
}
            
        </style>

        <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        
        <script> var baseUrl = '{{ url('api/v1') }}'; </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content" id="app">
                <div class="container">
                    <div style="text-align:center;">    
                        @if(!Auth::check())
                            <button @click="userLogin">Login</button> 
                        @else
                            <a href="{{ route('logout') }}" class="btn">Logout</a> 
                        @endif
                        <button @click="refresh" v-if="token!=null" class="btn">@{{ btnText }}</button>
                        @{{ notif }}
                    </div>
                    <ul>
                        <comp-quote v-for="quote in quotes" :quote="quote.quote"></comp-quote>
                    </ul>

                    <div class="login-container" v-if="btnLoginClick">
                    <p style="color:red">{{Session::get('error')}}</p> 
                        <form action="{{ route('login') }}" method="post">
                        @csrf
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            <input type="password" name="password" placeholder="password" required>
                            <p><Button>Login</Button></p> 
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <script>
       
        var vm=new Vue({
            el: '#app',
            data: {
                quotes:[],
                btnText:'Refresh',
                btnLoginClick:false,
                token:null,
                notif:null,
            },
            mounted() {
                this.getQuotes()
            },
            methods: {
                getQuotes(){
                    if(this.token!=null){
                        this.btnText = "Please Wait.."
                        axios.get(baseUrl+'/quotes/'+this.token).then(response=>{
                            if(response.data.success==true){
                                this.quotes = response.data.datas
                                this.btnText = "Refresh"
                                
                            }
                        }).catch(err=>{
                            console.log(err);
                            this.btnText = "Refresh"
                        }) 
                    }
                    
                },
                refresh(){
                    this.getQuotes()
                    this.notif = null
                },
                userLogin(){
                    this.btnLoginClick=true
                }
            },
        })

        Vue.component('comp-quote', {
            props: {quote:null},
            template: '<li>@{{ quote }}</li>'
        })
        @if (Auth::check())
            vm.token = '{{ Auth::user()->api_token }}';
        @else 
            vm.btnLoginClick=true
        @endif

        @if (Session::has('success'))
            vm.getQuotes()
            vm.notif = '{{Session::get('success')}}';
        @endif

        @if(Session::has('error'))
            vm.btnLoginClick = true;
        @endif
        </script>
    </body>
</html>
