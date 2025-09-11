<x-filament::widget>
    <div class="grid gap-5 lg:grid-cols-2">
        {{-- Today Income Stat --}}
        <div class="p-1.5">
            {{ $this->getDailyIncomeStat() }}
        </div>

        {{-- Total Income Stat with Integrated Dropdown --}}
        <div class="relative ">
            {{-- Elegant Dropdown Selector Inside Widget --}}
            <div class="absolute z-10 right-4 top-4">
                <x-filament::dropdown placement="bottom-end">
                    <x-slot name="trigger">
                        <button type="button" class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium rounded-lg bg-white border border-gray-200 text-gray-700 shadow-xs hover:bg-gray-50 hover:border-gray-300 transition-all duration-150 ease-in-out">
                            <span class="whitespace-nowrap">{{ $this->getTimeRangeOptions()[$this->timeRange] }}</span>
                            <x-heroicon-s-chevron-down class="h-3.5 w-3.5 text-gray-500" />
                        </button>
                    </x-slot>

                    <x-filament::dropdown.list class="min-w-[140px] py-1.5 rounded-lg shadow-md border border-gray-100 bg-white">
                        @foreach($this->getTimeRangeOptions() as $value => $label)
                            <x-filament::dropdown.list.item
                                wire:click="setTimeRange('{{ $value }}')"
                                :active="$this->timeRange === $value"
                                class="px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
                            >
                                <div class="flex items-center gap-2">
                                    @if($this->timeRange === $value)
                                        <x-heroicon-s-check class="h-3.5 w-3.5 text-primary-500" />
                                    @endif
                                    <span class="{{ $this->timeRange === $value ? 'font-medium text-primary-600' : 'text-gray-600' }}">{{ $label }}</span>
                                </div>
                            </x-filament::dropdown.list.item>
                        @endforeach
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div>

            {{-- Stat Card with Proper Spacing --}}
            <div class="pt-1">
                {{ $this->getTotalIncomeStat() }}
            </div>
        </div>
    </div>
</x-filament::widget>
