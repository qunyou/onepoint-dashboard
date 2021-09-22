@section('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}
    {{-- <script src="{{ url('assets/js/vehicle.js') }}"></script> --}}
    <script type="module">
        import {
            createApp,
            ref,
            reactive,
            onBeforeMount
        } from 'https://cdn.jsdelivr.net/npm/vue@3.1.5/dist/vue.esm-browser.js';
        const EventHandling = {
            setup() {
                const env = reactive({
                    isReady: false
                });
                const navi = reactive({
                    mobile_menu_show: false,
                    navi_1: false,
                    navi_2: false,
                    navi_3: false,
                    navi_4: false
                });
                @yield('vue_reactive')

                {{-- 導覽展開收合 --}}
                function naviClick(navi_str) {
                    let current_status = !navi[navi_str];
                    Object.keys(navi).forEach(v => navi[v] = false);
                    navi[navi_str] = current_status;
                }
                @yield('vue_function')
                // onBeforeMount(() => {
                //     env.isReady = true;
                //     axios.get('{{ url('api/vehicle-init') }}')
                //         .then(function(response) {
                //             {{-- console.log(response.data); --}}
                //         })
                //         .catch(function(error) {
                //             console.log(error);
                //         });
                // })
                return {
                    env,
                    navi,
                    naviClick,
                    @yield('vue_return')
                }
            }
        }
        const app = createApp(EventHandling);

        // 邊欄選單展開
        app.component('button-collapse', {
            props: [
                'item_name'
            ],
            data() {
                return {
                    active: false,
                }
            },
            template: `
            <a href="#" class="bg-gray-200 text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-md" aria-current="page" @click.prevent="active = !active">
                <slot name='item_name'></slot>
            </a>
            <div class="mt-1 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button" tabindex="-1" v-show="active">
                <div class="py-1" role="none">
                    <slot></slot>
                </div>
            </div>`
        })
        app.mount('#app');
    </script>
@show
