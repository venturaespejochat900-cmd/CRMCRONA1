<div>
    @if($beforeTableSlot)
        <div class="mt-8">
            @include($beforeTableSlot)
        </div>
    @endif
    <div class="relative">
        <div class="flex items-center justify-between mb-1">
            <div class="flex-grow items-center h-10">
                @if($this->searchableColumns()->count())
                    <div class="flex rounded-lg w-96 shadow-sm">
                        <div class="relative flex-grow focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-900 dark:text-gray-900" viewBox="0 0 20 20" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            @if($modal)
                            <input wire:model.debounce.500ms="search" class="block w-full py-3 pl-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none text-gray-900" style="height: 40px !important; width:370px !important ;" placeholder="{{__('Buscar por')}} {{ $this->searchableColumns()->map->label->join(', ') }}" type="text" />
                            @else
                            <input wire:model.debounce.500ms="search" class="block w-full pl-10 text-sm border-gray-300 leading-4 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 focus:outline-none text-gray-900" style="height: 40px !important; width:470px !important ;" placeholder="{{__('Buscar por')}} {{ $this->searchableColumns()->map->label->join(', ') }}" type="text" />
                            @endif
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                @if($tarifa)
                                <button wire:click="$set('search', null)" class="text-gray-300 hover:text-red-600 focus:outline-none" id="refrescarTablaTarifa" name="refrescar">
                                    <x-icons.x-circle class="w-5 h-5 stroke-current" />
                                </button>
                                @elseif($pedido)
                                <button wire:click="$set('search', null)" class="text-gray-300 hover:text-red-600 focus:outline-none" id="refrescarTablaPedido" name="refrescar">
                                    <x-icons.x-circle class="w-5 h-5 stroke-current" />
                                </button>
                                @else
                                <button wire:click="$set('search', null)" class="text-gray-300 hover:text-red-600 focus:outline-none" id="refrescarTabla" name="refrescar">
                                    <x-icons.x-circle class="w-5 h-5 stroke-current" />
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if($this->activeFilters)
                <span class="text-xl text-blue-400 uppercase">FILTERS ACTIVE</span>
            @endif

            <div class="flex flex-wrap items-center space-x-1">
                <x-icons.cog wire:loading class="text-gray-400 h-9 w-9 animate-spin" />

                    @if($this->activeFilters)
                        <button wire:click="clearAllFilters" class="flex items-center px-3 text-xs font-medium tracking-wider text-red-500 uppercase bg-white border border-red-400 space-x-2 rounded-md leading-4 hover:bg-red-200 focus:outline-none"><span>{{ __('Reset') }}</span>
                            <x-icons.x-circle class="m-2" />
                        </button>
                    @endif

                    @if(count($this->massActionsOptions))
                        <div class="flex items-center justify-center space-x-1">
                            <label for="datatables_mass_actions">{{ __('With selected') }}:</label>
                            <select wire:model="massActionOption" class="px-3 text-xs font-medium tracking-wider uppercase bg-white border border-green-400 space-x-2 rounded-md leading-4 focus:outline-none" id="datatables_mass_actions">
                                <option value="">{{ __('Choose...') }}</option>
                                @foreach($this->massActionsOptions as $group => $items)
                                    @if(!$group)
                                        @foreach($items as $item)
                                            <option value="{{$item['value']}}">{{$item['label']}}</option>
                                        @endforeach
                                    @else
                                        <optgroup label="{{$group}}">
                                            @foreach($items as $item)
                                                <option value="{{$item['value']}}">{{$item['label']}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            <button
                                wire:click="massActionOptionHandler"
                                class="flex items-center px-4 py-2 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 rounded-md leading-4 hover:bg-green-200 focus:outline-none" type="submit" title="Submit"
                                                                                                                                                                                                                                          >Go</button>
                        </div>
                    @endif

                    @if($exportable)
                        <div x-data="{ init() {
                        window.livewire.on('startDownload', link => window.open(link, '_blank'))
                        } }" x-init="init">
                        <button wire:click="export" class="flex items-center px-3 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 space-x-2 rounded-md leading-4 hover:bg-green-200 focus:outline-none"><span>{{ __('Excel') }}</span>
                            <x-icons.excel class="m-2" /></button>
                        </div>
                    @endif

                    @if($exportablePdf)
                        <div x-data="{ init() {
                            window.livewire.on('startDownload', link => window.open(link,'_blank'))
                        } }" x-init="init">
                            <button wire:click="exportPdf" class="flex items-center space-x-2 px-3 border border-red-400 rounded-md bg-white text-red-500 text-xs leading-4 font-medium uppercase tracking-wider hover:bg-red-200 focus:outline-none"><span>{{ __('Pdf') }}</span>
                            <x-icons.pdf class="m-2" /></button>
                        </div>
                    @endif

                    @if($hideable === 'select')
                        @include('datatables::hide-column-multiselect')
                    @endif

                    @foreach ($columnGroups as $name => $group)
                        <button wire:click="toggleGroup('{{ $name }}')"
                                class="px-3 py-2 text-xs font-medium tracking-wider text-green-500 uppercase bg-white border border-green-400 rounded-md leading-4 hover:bg-green-200 focus:outline-none">
                            <span class="flex items-center h-5">{{ isset($this->groupLabels[$name]) ? __($this->groupLabels[$name]) : __('Toggle :group', ['group' => $name]) }}</span>
                        </button>
                    @endforeach
                    </div>
        </div>
        <!-- -------------------------------------- -->
        <!-- BOTÓN PARA INSERTAR DATOS PRESCRIPTOR  -->
        <!-- -------------------------------------- -->
        @if($prescriptor)
        <div x-data="data()" x-init="start()">
            <div class="flex justify-between items-center mb-1">
                <button @click={show=true} class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>{{ __('+ Nuevo Comisionista') }}</span>
                </button>           
            </div>
            <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" name="modalPrescriptor" id="modalPrescriptor">
                <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                    <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                        <button @click={show=false} class="fill-current  w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                        <div class="px-6 py-3 text-xl border-b font-bold text-gray-900">Nuevo Comisionista</div>
                            <div class="p-6 flex-grow">
                                
                                    @include('comisionistas.create')
                                
                            </div>
                        <div class="px-6 py-3 border-t">
                            <div class="flex justify-end">
                                <!-- <button @click={show=false} x-on:click="console.log('hola')" type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <!-- <button onclick="window.location.reload()"  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
            </div>
        </div>
        
        
        <script type="text/javascript">
            function data(){
                return{
                    show: null,
                    start(){
                        this.show = false;
                    }
                }
            }
        </script>
        @endif

        <!-- ------------------------------ -->
        <!-- BOTÓN PARA INSERTAR Potencial  -->
        <!-- ------------------------------ -->
        @if($potencial)
        <div x-data="data()" x-init="start()">
            <div class="flex justify-between items-center mb-1">
                <button @click={show=true} class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>{{ __('+ Nuevo Potencial') }}</span>
                </button>           
            </div>
            <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" name="modalPrescriptor" id="modalPrescriptor">
                <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                    <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                        <button @click={show=false} class="fill-current  w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                        <div class="px-6 py-3 text-xl border-b font-bold text-gray-900">Nuevo Potencial</div>
                            <div class="p-6 flex-grow">                                
                                    @include('potenciales.create')                                
                            </div>
                        <div class="px-6 py-3 border-t">
                            <div class="flex justify-end">
                                <!-- <button @click={show=false} x-on:click="console.log('hola')" type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <!-- <button onclick="window.location.reload()"  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
            </div>
        </div>
        
        
        <script type="text/javascript">
            function data(){
                return{
                    show: null,
                    start(){
                        this.show = false;
                    }
                }
            }
        </script>
        @endif
        

        <!-- ---------------------------------------------------- -->
        <!-- BOTÓN PARA INSERTAR DATOS Clientes y Autorizaciones  -->
        <!-- ---------------------------------------------------- -->
        @if($autorizaciones)
        <div x-data="data()" x-init="start()">
            <div class="flex justify-between items-center mb-1">
                <button @click={show=true} class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>{{ __('+ Nuevo Cliente') }}</span>
                </button>           
            </div>
            <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" name="modalPrescriptor" id="modalPrescriptor">
                <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                    <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                        <button @click={show=false} class="fill-current  w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                        <div class="px-6 py-3 text-xl border-b font-bold text-gray-900">Nuevo Cliente</div>
                            <div class="p-6 flex-grow">
                                <form @submit.prevent="console.log('intentando refrescar formulario')">
                                    @include('clientes.create')
                                </form>
                            </div>
                        <div class="px-6 py-3 border-t">
                            <div class="flex justify-end">
                                <!-- <button @click={show=false} x-on:click="console.log('hola')" type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button>
                                <!-- <button onclick="window.location.reload()"  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
            </div>
        </div>
        
        
        <script type="text/javascript">
            function data(){
                return{
                    show: null,
                    start(){
                        this.show = false;
                    }
                }
            }
        </script>
        @endif

        @if($tarifario)
        <div x-data="data()" x-init="start()">
            <div class="flex justify-between items-center mb-1">
                <button @click={show=true} class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>{{ __('+ Artículo Tarifa') }}</span>
                </button>           
            </div>
            <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" name="modalPrescriptor" id="modalPrescriptor">
                <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                    <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                        <button @click={show=false} class="fill-current  w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                        <div class="px-6 py-3 text-xl border-b font-bold text-gray-900">Nuevo Articulo Tarifa</div>
                            <div class="p-6 flex-grow">
                                <form @submit.prevent="console.log('intentando refrescar formulario')">
                                    @include('comisionistas.stock.create')
                                </form>
                            </div>
                        <div class="px-6 py-3 border-t">
                            <div class="flex justify-end">
                                <!-- <button @click={show=false} x-on:click="console.log('hola')" type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                                <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button>
                                <!-- <button onclick="window.location.reload()"  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1" >Cerrar</Button> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
            </div>
        </div>
        
        
        <script type="text/javascript">
            function data(){
                return{
                    show: null,
                    start(){
                        this.show = false;
                    }
                }
            }
        </script>
        @endif





        @if($hideable === 'buttons')
            <div class="p-2 grid grid-cols-8 gap-2">
                @foreach($this->columns as $index => $column)
                    @if ($column['hideable'])
                        <button wire:click.prefetch="toggle('{{ $index }}')" class="px-3 py-2 rounded text-white text-xs focus:outline-none
                        {{ $column['hidden'] ? 'bg-blue-100 hover:bg-blue-300 text-blue-600' : 'bg-blue-500 hover:bg-blue-800' }}">
                            {{ $column['label'] }}
                        </button>
                    @endif
                @endforeach
            </div>
        @endif

        <div wire:loading.class="opacity-50" class="rounded-lg @unless($complex || $this->hidePagination) rounded-b-none @endunless shadow-lg bg-white max-w-screen overflow-x-scroll border-4 @if($this->activeFilters) border-blue-500 @else border-transparent @endif @if($complex) rounded-b-none border-b-0 @endif">
            <div>
                <div class="table min-w-full align-middle">
                    @unless($this->hideHeader)
                        <div class="table-row divide-x divide-gray-200">
                            @foreach($this->columns as $index => $column)
                                @if($hideable === 'inline')
                                    @include('datatables::header-inline-hide', ['column' => $column, 'sort' => $sort])
                                @elseif($column['type'] === 'checkbox')
                                    @unless($column['hidden'])
                                        <div class="flex justify-center table-cell w-32 h-12 px-6 py-4 overflow-hidden text-xs font-medium tracking-wider text-left text-gray-500 uppercase align-top border-b border-gray-200 bg-gray-50 leading-4 focus:outline-none">
                                            <div class="px-3 py-1 rounded @if(count($selected)) bg-orange-400 @else bg-gray-200 @endif text-white text-center">
                                                {{ count($selected) }}
                                            </div>
                                        </div>
                                    @endunless
                                @else
                                    @include('datatables::header-no-hide', ['column' => $column, 'sort' => $sort])
                                @endif
                            @endforeach
                        </div>

                        <!-- <div class="table-row bg-blue-100 divide-x divide-blue-200">
                            @foreach($this->columns as $index => $column)
                                @if($column['hidden'])
                                    @if($hideable === 'inline')
                                        <div class="table-cell w-5 overflow-hidden align-top bg-blue-100"></div>
                                    @endif
                                @elseif($column['type'] === 'checkbox')
                                    <div
                                        @if (isset($column['tooltip']['text'])) title="{{ $column['tooltip']['text'] }}" @endif
                                                                                class="flex flex-col items-center h-full px-6 py-5 overflow-hidden text-xs font-medium tracking-wider text-left text-gray-500 uppercase align-top bg-blue-100 border-b border-gray-200 leading-4 space-y-2 focus:outline-none">
                                        <div>{{ __('SELECT ALL') }}</div>
                                        <div>
                                            <input type="checkbox" wire:click="toggleSelectAll" @if(count($selected) === $this->results->total()) checked @endif class="w-4 h-4 mt-1 text-blue-600 form-checkbox transition duration-150 ease-in-out" />
                                        </div>
                                    </div>
                                @elseif($column['type'] === 'label')
                                    <div class="table-cell overflow-hidden align-top">
                                        {{ $column['label'] ?? '' }}
                                    </div>
                                @else
                                    <div class="table-cell overflow-hidden align-top">
                                        @isset($column['filterable'])
                                        @if( is_iterable($column['filterable']) )
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.select', ['index' => $index, 'name' => $column['label'], 'options' => $column['filterable']])
                                            </div>
                                        @else
                                            <div wire:key="{{ $index }}">
                                                @include('datatables::filters.' . ($column['filterView'] ?? $column['type']), ['index' => $index, 'name' => $column['label']])
                                            </div>
                                        @endif
                                    @endisset
                                    </div>
                                @endif
                            @endforeach
                        </div> -->
                    @endif
                    @forelse($this->results as $row)
                        <div class="table-row p-1 {{ $this->rowClasses($row, $loop) }}">
                            @foreach($this->columns as $column)
                                @if($column['hidden'])
                                    @if($hideable === 'inline')
                                        <div class="table-cell w-5 overflow-hidden align-top"></div>
                                    @endif
                                @elseif($column['type'] === 'checkbox')
                                    @include('datatables::checkbox', ['value' => $row->checkbox_attribute])
                                @elseif($column['type'] === 'label')
                                    @include('datatables::label')
                                @else
                                    <div class="table-cell px-6 py-2 whitespace-no-wrap @if($column['align'] === 'right') text-right @elseif($column['align'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }}">
                                        {!! $row->{$column['name']} !!}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @empty
                        <p class="p-3 text-lg text-teal-600">
                            {{ __("No hay nada para mostrar en este momento") }}
                        </p>
                    @endforelse

                    @if ($this->hasSummaryRow())
                        <div class="table-row p-1">
                            @foreach($this->columns as $column)
                                @if ($column['summary'])
                                    <div class="table-cell px-6 py-2 whitespace-no-wrap @if($column['align'] === 'right') text-right @elseif($column['align'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }}">
                                        {{ $this->summarize($column['name']) }}
                                    </div>
                                @else
                                    <div class="table-cell"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @unless($this->hidePagination)
            <div class="max-w-screen bg-white @unless($complex) rounded-b-lg @endunless border-4 border-t-0 border-b-0 @if($this->activeFilters) border-blue-500 @else border-transparent @endif">
                <div class="items-center justify-between p-2 sm:flex">
                    {{-- check if there is any data --}}
                    @if(count($this->results))
                        <div class="flex items-center my-2 sm:my-0">
                            <select name="perPage" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 form-select leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 text-gray-900 dark:text-gray-900" wire:model="perPage">
                                @foreach(config('livewire-datatables.per_page_options', [ 13, 25, 50, 100 ]) as $per_page_option)
                                    <option class="!hover:bg-primary-100 !dark:hover:bg-primary" value="{{ $per_page_option }}">{{ $per_page_option }}</option>
                                @endforeach
                                <option value="99999999">{{__('All')}}</option>
                            </select>
                        </div>

                        <div class="my-4 sm:my-0">
                            <div class="lg:hidden">
                                <span class="space-x-2">{{ $this->results->links('datatables::tailwind-simple-pagination') }}</span>
                            </div>

                            <div class="justify-center hidden lg:flex">
                                <span>{{ $this->results->links('datatables::tailwind-pagination') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end text-gray-900">
                            {{__('Results')}} {{ $this->results->firstItem() }} - {{ $this->results->lastItem() }} {{__('of')}}
                            {{ $this->results->total() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if($complex)
        <div class="bg-gray-50 px-4 py-4 rounded-b-lg rounded-t-none shadow-lg border-4 @if($this->activeFilters) border-blue-500 @else border-transparent @endif @if($complex) border-t-0 @endif">
            <livewire:complex-query :columns="$this->complexColumns" :persistKey="$this->persistKey" :savedQueries="method_exists($this, 'getSavedQueries') ? $this->getSavedQueries() : null" />
        </div>
    @endif

    @if($afterTableSlot)
        <div class="mt-8">
            @include($afterTableSlot)
        </div>
    @endif
    <span class="hidden text-sm text-left text-center text-right text-gray-900 bg-gray-100 bg-yellow-100 leading-5 bg-gray-50"></span>
</div>
