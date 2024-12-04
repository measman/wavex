<script setup>
import { ref, computed, onMounted, reactive } from "vue";
import AdminLayout from "./Components/AdminLayout.vue";
import { usePage } from "@inertiajs/vue3";
import ApexCharts from "apexcharts";
import { array } from "i/lib/util";
import $ from 'jquery';
import Swal from 'sweetalert2';

// Define props to receive data
const props = defineProps({
    daily_status: Object,
    todayexchangerate: Array,
    wallet: Array,
    currencies: Array,
    token:String
});



const currentPage = ref(1);
const itemsPerPage = 5;

// Paginated data
const paginatedData = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return props.todayexchangerate.slice(startIndex, endIndex);
});

// Total number of pages
const totalPages = computed(() => {
    return Math.ceil(props.todayexchangerate.length / itemsPerPage);
});

// Change page
const goToPage = (page) => {
    if (page > 0 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

//let daily_status=usePage().props.daily_status.data;

const options = {
    // add data series via arrays, learn more here: https://apexcharts.com/docs/series/
    series: [
        {
            name: "Developer Edition",
            data: [1500, 1418, 1456, 1526, 1356, 1256],
            color: "#1A56DB",
        },
        {
            name: "Designer Edition",
            data: [643, 413, 765, 412, 1423, 1731],
            color: "#7E3BF2",
        },
    ],
    chart: {
        height: "100%",
        maxWidth: "100%",
        type: "area",
        fontFamily: "Inter, sans-serif",
        dropShadow: {
            enabled: false,
        },
        toolbar: {
            show: false,
        },
    },
    tooltip: {
        enabled: true,
        x: {
            show: false,
        },
    },
    legend: {
        show: false,
    },
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: 0.55,
            opacityTo: 0,
            shade: "#1C64F2",
            gradientToColors: ["#1C64F2"],
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        width: 6,
    },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: 0,
        },
    },
    xaxis: {
        categories: [
            "01 February",
            "02 February",
            "03 February",
            "04 February",
            "05 February",
            "06 February",
            "07 February",
        ],
        labels: {
            show: true,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        show: true,
        labels: {
            formatter: function (value) {
                return "$" + value;
            },
        },
    },
};

onMounted(() => {
    const chartElement = document.getElementById("area-chart");
    if (chartElement) {
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    } else {
        console.error("Chart element not found");
    }
});
onMounted(() => {
    if (props.wallet[0] == null) {
        const walletModal = document.getElementById("walletModal");
        if (walletModal) {
            walletModal.classList.remove("hidden");
        }
    }
});
let baseUrl = window.location.origin;
let endpointforopeningbalance = '/api/openingbalance';
const form = reactive({});
const walletsubmit = () => {
    // Collect all currency IDs and their corresponding balances
    const balances = Object.keys(form).map(currencyId => ({
        currencyId,
        balance: form[currencyId]
    }));
    console.log(balances); 
    $.ajax({
        url: baseUrl + endpointforopeningbalance,
        headers: {
            'Authorization': 'Bearer ' + usePage().props.token
        },
        method: "POST",
        data: form,
        dataType: "JSON",
        success: function (data) {
            Swal.fire({
                title: 'Success',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
                buttonsStyling: true
            });
            walletModal.classList.add("hidden");
        }
    });
};
</script>
<style>
#walletModal {
    position: fixed;
    /* Ensures it's positioned relative to the viewport */
    inset: 0;
    /* Covers the entire viewport */
    z-index: 1000;
    /* High enough to stay above other elements */
}
</style>

<template>
    <AdminLayout>
        <div id="walletModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-[60rem] max-h-[60vh] overflow-y-auto relative z-50">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b p-4">
                    <h2 class="text-lg font-bold">Opening Balance</h2>
                </div>
                <!-- Modal Body -->
                <form @submit.prevent="walletsubmit">
                    <div v-for="(currency, index) in currencies" :key="currency.id" class="px-4 py-3">
                        <label :for="'balance_' + currency.id" class="block text-sm font-medium text-gray-700">{{
                            currency.code }}</label>
                        <input type="number" :id="'balance_' + currency.id" v-model="form[currency.id]"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter opening balance" />
                    </div>
                    <!-- Submit button -->
                    <div class="px-4 py-3 bg-gray-50 text-right">
                        <button type="submit" class="bg-indigo-600 text-white rounded-md px-4 py-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg p-4 shadow-md mb-4 text-right">
            Daily Status : {{ props.daily_status.data.status }}
        </div>
        <div class="grid w-full grid-cols-1 gap-4 mt-4 xl:grid-cols-2 2xl:grid-cols-3">
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">
                        New products
                    </h3>
                    <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">2,340</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                        <span class="flex items-center mr-1.5 text-sm text-green-500 dark:text-green-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                </path>
                            </svg>
                            12.5%
                        </span>
                        Since last month
                    </p>
                </div>
                <div class="w-full" id="new-products-chart" style="min-height: 155px">
                    <div id="apexcharts6moybdxy" class="apexcharts-canvas apexcharts6moybdxy apexcharts-theme-light"
                        style="width: 239px; height: 140px">
                        <svg id="SvgjsSvg1957" width="239" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                            class="apexcharts-svg hovering-zoom" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                            style="background: transparent">
                            <g id="SvgjsG1959" class="apexcharts-inner apexcharts-graphical"
                                transform="translate(12, 30)">
                                <defs id="SvgjsDefs1958">
                                    <linearGradient id="SvgjsLinearGradient1963" x1="0" y1="0" x2="0" y2="1">
                                        <stop id="SvgjsStop1964" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                            offset="0"></stop>
                                        <stop id="SvgjsStop1965" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1"></stop>
                                        <stop id="SvgjsStop1966" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1"></stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMask6moybdxy">
                                        <rect id="SvgjsRect1968" width="226" height="83" x="-4.5" y="-2.5" rx="0" ry="0"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                        </rect>
                                    </clipPath>
                                    <clipPath id="forecastMask6moybdxy"></clipPath>
                                    <clipPath id="nonForecastMask6moybdxy"></clipPath>
                                    <clipPath id="gridRectMarkerMask6moybdxy">
                                        <rect id="SvgjsRect1969" width="221" height="82" x="-2" y="-2" rx="0" ry="0"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                        </rect>
                                    </clipPath>
                                </defs>
                                <rect id="SvgjsRect1967" width="27.9" height="78" x="1.5500000000000007" y="0" rx="0"
                                    ry="0" opacity="1" stroke-width="0" stroke-dasharray="3"
                                    fill="url(#SvgjsLinearGradient1963)" class="apexcharts-xcrosshairs" y2="78"
                                    filter="none" fill-opacity="0.9" x1="1.5500000000000007" x2="1.5500000000000007">
                                </rect>
                                <g id="SvgjsG1988" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG1989" class="apexcharts-xaxis-texts-g" transform="translate(0, 4)"></g>
                                </g>
                                <g id="SvgjsG1998" class="apexcharts-grid">
                                    <g id="SvgjsG1999" class="apexcharts-gridlines-horizontal" style="display: none">
                                        <line id="SvgjsLine2001" x1="0" y1="0" x2="217" y2="0" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2002" x1="0" y1="19.5" x2="217" y2="19.5" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2003" x1="0" y1="39" x2="217" y2="39" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2004" x1="0" y1="58.5" x2="217" y2="58.5" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2005" x1="0" y1="78" x2="217" y2="78" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                    </g>
                                    <g id="SvgjsG2000" class="apexcharts-gridlines-vertical" style="display: none"></g>
                                    <line id="SvgjsLine2007" x1="0" y1="78" x2="217" y2="78" stroke="transparent"
                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                    <line id="SvgjsLine2006" x1="0" y1="1" x2="0" y2="78" stroke="transparent"
                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                </g>
                                <g id="SvgjsG1970" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG1971" class="apexcharts-series" rel="1" seriesName="Quantity"
                                        data:realIndex="0">
                                        <path id="SvgjsPath1975"
                                            d="M 1.5500000000000007 78L 1.5500000000000007 14.700000000000003Q 1.5500000000000007 11.700000000000003 4.550000000000001 11.700000000000003L 21.45 11.700000000000003Q 24.45 11.700000000000003 24.45 14.700000000000003L 24.45 14.700000000000003L 24.45 78L 24.45 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 1.5500000000000007 78L 1.5500000000000007 14.700000000000003Q 1.5500000000000007 11.700000000000003 4.550000000000001 11.700000000000003L 21.45 11.700000000000003Q 24.45 11.700000000000003 24.45 14.700000000000003L 24.45 14.700000000000003L 24.45 78L 24.45 78z"
                                            pathFrom="M 1.5500000000000007 78L 1.5500000000000007 78L 24.45 78L 24.45 78L 24.45 78L 24.45 78L 24.45 78L 1.5500000000000007 78"
                                            cy="11.700000000000003" cx="30.049999999999997" j="0" val="170"
                                            barHeight="66.3" barWidth="27.9"></path>
                                        <path id="SvgjsPath1977"
                                            d="M 32.55 78L 32.55 10.800000000000011Q 32.55 7.800000000000011 35.55 7.800000000000011L 52.449999999999996 7.800000000000011Q 55.449999999999996 7.800000000000011 55.449999999999996 10.800000000000011L 55.449999999999996 10.800000000000011L 55.449999999999996 78L 55.449999999999996 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 32.55 78L 32.55 10.800000000000011Q 32.55 7.800000000000011 35.55 7.800000000000011L 52.449999999999996 7.800000000000011Q 55.449999999999996 7.800000000000011 55.449999999999996 10.800000000000011L 55.449999999999996 10.800000000000011L 55.449999999999996 78L 55.449999999999996 78z"
                                            pathFrom="M 32.55 78L 32.55 78L 55.449999999999996 78L 55.449999999999996 78L 55.449999999999996 78L 55.449999999999996 78L 55.449999999999996 78L 32.55 78"
                                            cy="7.800000000000011" cx="61.05" j="1" val="180"
                                            barHeight="70.19999999999999" barWidth="27.9"></path>
                                        <path id="SvgjsPath1979"
                                            d="M 63.55 78L 63.55 17.040000000000006Q 63.55 14.040000000000006 66.55 14.040000000000006L 83.44999999999999 14.040000000000006Q 86.44999999999999 14.040000000000006 86.44999999999999 17.040000000000006L 86.44999999999999 17.040000000000006L 86.44999999999999 78L 86.44999999999999 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 63.55 78L 63.55 17.040000000000006Q 63.55 14.040000000000006 66.55 14.040000000000006L 83.44999999999999 14.040000000000006Q 86.44999999999999 14.040000000000006 86.44999999999999 17.040000000000006L 86.44999999999999 17.040000000000006L 86.44999999999999 78L 86.44999999999999 78z"
                                            pathFrom="M 63.55 78L 63.55 78L 86.44999999999999 78L 86.44999999999999 78L 86.44999999999999 78L 86.44999999999999 78L 86.44999999999999 78L 63.55 78"
                                            cy="14.040000000000006" cx="92.05" j="2" val="164"
                                            barHeight="63.959999999999994" barWidth="27.9"></path>
                                        <path id="SvgjsPath1981"
                                            d="M 94.55 78L 94.55 24.450000000000003Q 94.55 21.450000000000003 97.55 21.450000000000003L 114.44999999999999 21.450000000000003Q 117.44999999999999 21.450000000000003 117.44999999999999 24.450000000000003L 117.44999999999999 24.450000000000003L 117.44999999999999 78L 117.44999999999999 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 94.55 78L 94.55 24.450000000000003Q 94.55 21.450000000000003 97.55 21.450000000000003L 114.44999999999999 21.450000000000003Q 117.44999999999999 21.450000000000003 117.44999999999999 24.450000000000003L 117.44999999999999 24.450000000000003L 117.44999999999999 78L 117.44999999999999 78z"
                                            pathFrom="M 94.55 78L 94.55 78L 117.44999999999999 78L 117.44999999999999 78L 117.44999999999999 78L 117.44999999999999 78L 117.44999999999999 78L 94.55 78"
                                            cy="21.450000000000003" cx="123.05" j="3" val="145" barHeight="56.55"
                                            barWidth="27.9"></path>
                                        <path id="SvgjsPath1983"
                                            d="M 125.55 78L 125.55 5.340000000000003Q 125.55 2.3400000000000034 128.55 2.3400000000000034L 145.45 2.3400000000000034Q 148.45 2.3400000000000034 148.45 5.340000000000003L 148.45 5.340000000000003L 148.45 78L 148.45 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 125.55 78L 125.55 5.340000000000003Q 125.55 2.3400000000000034 128.55 2.3400000000000034L 145.45 2.3400000000000034Q 148.45 2.3400000000000034 148.45 5.340000000000003L 148.45 5.340000000000003L 148.45 78L 148.45 78z"
                                            pathFrom="M 125.55 78L 125.55 78L 148.45 78L 148.45 78L 148.45 78L 148.45 78L 148.45 78L 125.55 78"
                                            cy="2.3400000000000034" cx="154.05" j="4" val="194" barHeight="75.66"
                                            barWidth="27.9"></path>
                                        <path id="SvgjsPath1985"
                                            d="M 156.55 78L 156.55 14.700000000000003Q 156.55 11.700000000000003 159.55 11.700000000000003L 176.45000000000002 11.700000000000003Q 179.45000000000002 11.700000000000003 179.45000000000002 14.700000000000003L 179.45000000000002 14.700000000000003L 179.45000000000002 78L 179.45000000000002 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 156.55 78L 156.55 14.700000000000003Q 156.55 11.700000000000003 159.55 11.700000000000003L 176.45000000000002 11.700000000000003Q 179.45000000000002 11.700000000000003 179.45000000000002 14.700000000000003L 179.45000000000002 14.700000000000003L 179.45000000000002 78L 179.45000000000002 78z"
                                            pathFrom="M 156.55 78L 156.55 78L 179.45000000000002 78L 179.45000000000002 78L 179.45000000000002 78L 179.45000000000002 78L 179.45000000000002 78L 156.55 78"
                                            cy="11.700000000000003" cx="185.05" j="5" val="170" barHeight="66.3"
                                            barWidth="27.9"></path>
                                        <path id="SvgjsPath1987"
                                            d="M 187.55 78L 187.55 20.550000000000004Q 187.55 17.550000000000004 190.55 17.550000000000004L 207.45000000000002 17.550000000000004Q 210.45000000000002 17.550000000000004 210.45000000000002 20.550000000000004L 210.45000000000002 20.550000000000004L 210.45000000000002 78L 210.45000000000002 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke="transparent"
                                            stroke-opacity="1" stroke-linecap="round" stroke-width="5"
                                            stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                            clip-path="url(#gridRectMask6moybdxy)"
                                            pathTo="M 187.55 78L 187.55 20.550000000000004Q 187.55 17.550000000000004 190.55 17.550000000000004L 207.45000000000002 17.550000000000004Q 210.45000000000002 17.550000000000004 210.45000000000002 20.550000000000004L 210.45000000000002 20.550000000000004L 210.45000000000002 78L 210.45000000000002 78z"
                                            pathFrom="M 187.55 78L 187.55 78L 210.45000000000002 78L 210.45000000000002 78L 210.45000000000002 78L 210.45000000000002 78L 210.45000000000002 78L 187.55 78"
                                            cy="17.550000000000004" cx="216.05" j="6" val="155"
                                            barHeight="60.449999999999996" barWidth="27.9"></path>
                                        <g id="SvgjsG1973" class="apexcharts-bar-goals-markers"
                                            style="pointer-events: none">
                                            <g id="SvgjsG1974" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1976" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1978" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1980" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1982" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1984" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG1986" className="apexcharts-bar-goals-groups"></g>
                                        </g>
                                    </g>
                                    <g id="SvgjsG1972" class="apexcharts-datalabels" data:realIndex="0"></g>
                                </g>
                                <line id="SvgjsLine2008" x1="0" y1="0" x2="217" y2="0" stroke="#b6b6b6"
                                    stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                    class="apexcharts-ycrosshairs"></line>
                                <line id="SvgjsLine2009" x1="0" y1="0" x2="217" y2="0" stroke-dasharray="0"
                                    stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                <g id="SvgjsG2010" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG2011" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG2012" class="apexcharts-point-annotations"></g>
                            </g>
                            <g id="SvgjsG1997" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                            <g id="SvgjsG1960" class="apexcharts-annotations"></g>
                        </svg>
                        <div class="apexcharts-legend" style="max-height: 70px"></div>
                        <div class="apexcharts-tooltip apexcharts-theme-light" style="left: 33.5px; top: 0px">
                            <div class="apexcharts-tooltip-title" style="
                                    font-family: Inter, sans-serif;
                                    font-size: 14px;
                                ">
                                01 Feb
                            </div>
                            <div class="apexcharts-tooltip-series-group apexcharts-active"
                                style="order: 1; display: flex">
                                <span class="apexcharts-tooltip-marker"
                                    style="background-color: rgb(26, 86, 219)"></span>
                                <div class="apexcharts-tooltip-text" style="
                                        font-family: Inter, sans-serif;
                                        font-size: 14px;
                                    ">
                                    <div class="apexcharts-tooltip-y-group">
                                        <span class="apexcharts-tooltip-text-y-label">Quantity: </span><span
                                            class="apexcharts-tooltip-text-y-value">170</span>
                                    </div>
                                    <div class="apexcharts-tooltip-goals-group">
                                        <span class="apexcharts-tooltip-text-goals-label"></span><span
                                            class="apexcharts-tooltip-text-goals-value"></span>
                                    </div>
                                    <div class="apexcharts-tooltip-z-group">
                                        <span class="apexcharts-tooltip-text-z-label"></span><span
                                            class="apexcharts-tooltip-text-z-value"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                            <div class="apexcharts-yaxistooltip-text"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="text-base font-normal text-gray-500 dark:text-gray-400">
                        Users
                    </h3>
                    <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl dark:text-white">2,340</span>
                    <p class="flex items-center text-base font-normal text-gray-500 dark:text-gray-400">
                        <span class="flex items-center mr-1.5 text-sm text-green-500 dark:text-green-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z">
                                </path>
                            </svg>
                            3,4%
                        </span>
                        Since last month
                    </p>
                </div>
                <div class="w-full" id="week-signups-chart" style="min-height: 155px">
                    <div id="apexchartss63ht3cf" class="apexcharts-canvas apexchartss63ht3cf apexcharts-theme-light"
                        style="width: 239px; height: 140px">
                        <svg id="SvgjsSvg2103" width="239" height="140" xmlns="http://www.w3.org/2000/svg" version="1.1"
                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                            style="background: transparent">
                            <g id="SvgjsG2105" class="apexcharts-inner apexcharts-graphical"
                                transform="translate(12, 30)">
                                <defs id="SvgjsDefs2104">
                                    <linearGradient id="SvgjsLinearGradient2109" x1="0" y1="0" x2="0" y2="1">
                                        <stop id="SvgjsStop2110" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)"
                                            offset="0"></stop>
                                        <stop id="SvgjsStop2111" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1"></stop>
                                        <stop id="SvgjsStop2112" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)"
                                            offset="1"></stop>
                                    </linearGradient>
                                    <clipPath id="gridRectMasks63ht3cf">
                                        <rect id="SvgjsRect2114" width="221" height="78" x="-2" y="0" rx="0" ry="0"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                        </rect>
                                    </clipPath>
                                    <clipPath id="forecastMasks63ht3cf"></clipPath>
                                    <clipPath id="nonForecastMasks63ht3cf"></clipPath>
                                    <clipPath id="gridRectMarkerMasks63ht3cf">
                                        <rect id="SvgjsRect2115" width="221" height="82" x="-2" y="-2" rx="0" ry="0"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff">
                                        </rect>
                                    </clipPath>
                                </defs>
                                <rect id="SvgjsRect2113" width="7.75" height="78" x="0" y="0" rx="0" ry="0" opacity="1"
                                    stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2109)"
                                    class="apexcharts-xcrosshairs" y2="78" filter="none" fill-opacity="0.9"></rect>
                                <g id="SvgjsG2141" class="apexcharts-xaxis" transform="translate(0, 0)">
                                    <g id="SvgjsG2142" class="apexcharts-xaxis-texts-g" transform="translate(0, 4)"></g>
                                </g>
                                <g id="SvgjsG2151" class="apexcharts-grid">
                                    <g id="SvgjsG2152" class="apexcharts-gridlines-horizontal" style="display: none">
                                        <line id="SvgjsLine2154" x1="0" y1="0" x2="217" y2="0" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2155" x1="0" y1="15.6" x2="217" y2="15.6" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2156" x1="0" y1="31.2" x2="217" y2="31.2" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2157" x1="0" y1="46.8" x2="217" y2="46.8" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2158" x1="0" y1="62.4" x2="217" y2="62.4" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                        <line id="SvgjsLine2159" x1="0" y1="78" x2="217" y2="78" stroke="#e0e0e0"
                                            stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline">
                                        </line>
                                    </g>
                                    <g id="SvgjsG2153" class="apexcharts-gridlines-vertical" style="display: none"></g>
                                    <line id="SvgjsLine2161" x1="0" y1="78" x2="217" y2="78" stroke="transparent"
                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                    <line id="SvgjsLine2160" x1="0" y1="1" x2="0" y2="78" stroke="transparent"
                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                </g>
                                <g id="SvgjsG2116" class="apexcharts-bar-series apexcharts-plot-series">
                                    <g id="SvgjsG2117" class="apexcharts-series" rel="1" seriesName="Users"
                                        data:realIndex="0">
                                        <rect id="SvgjsRect2120" width="7.75" height="78" x="11.625" y="0" rx="3" ry="3"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2122"
                                            d="M 11.625 78L 11.625 46.315999999999995Q 11.625 43.315999999999995 14.625 43.315999999999995L 16.375 43.315999999999995Q 19.375 43.315999999999995 19.375 46.315999999999995L 19.375 46.315999999999995L 19.375 78L 19.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 11.625 78L 11.625 46.315999999999995Q 11.625 43.315999999999995 14.625 43.315999999999995L 16.375 43.315999999999995Q 19.375 43.315999999999995 19.375 46.315999999999995L 19.375 46.315999999999995L 19.375 78L 19.375 78z"
                                            pathFrom="M 11.625 78L 11.625 78L 19.375 78L 19.375 78L 19.375 78L 19.375 78L 19.375 78L 11.625 78"
                                            cy="43.315999999999995" cx="42.625" j="0" val="1334"
                                            barHeight="34.684000000000005" barWidth="7.75"></path>
                                        <rect id="SvgjsRect2123" width="7.75" height="78" x="42.625" y="0" rx="3" ry="3"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2125"
                                            d="M 42.625 78L 42.625 17.689999999999998Q 42.625 14.689999999999998 45.625 14.689999999999998L 47.375 14.689999999999998Q 50.375 14.689999999999998 50.375 17.689999999999998L 50.375 17.689999999999998L 50.375 78L 50.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 42.625 78L 42.625 17.689999999999998Q 42.625 14.689999999999998 45.625 14.689999999999998L 47.375 14.689999999999998Q 50.375 14.689999999999998 50.375 17.689999999999998L 50.375 17.689999999999998L 50.375 78L 50.375 78z"
                                            pathFrom="M 42.625 78L 42.625 78L 50.375 78L 50.375 78L 50.375 78L 50.375 78L 50.375 78L 42.625 78"
                                            cy="14.689999999999998" cx="73.625" j="1" val="2435" barHeight="63.31"
                                            barWidth="7.75"></path>
                                        <rect id="SvgjsRect2126" width="7.75" height="78" x="73.625" y="0" rx="3" ry="3"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2128"
                                            d="M 73.625 78L 73.625 35.422Q 73.625 32.422 76.625 32.422L 78.375 32.422Q 81.375 32.422 81.375 35.422L 81.375 35.422L 81.375 78L 81.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 73.625 78L 73.625 35.422Q 73.625 32.422 76.625 32.422L 78.375 32.422Q 81.375 32.422 81.375 35.422L 81.375 35.422L 81.375 78L 81.375 78z"
                                            pathFrom="M 73.625 78L 73.625 78L 81.375 78L 81.375 78L 81.375 78L 81.375 78L 81.375 78L 73.625 78"
                                            cy="32.422" cx="104.625" j="2" val="1753" barHeight="45.578"
                                            barWidth="7.75"></path>
                                        <rect id="SvgjsRect2129" width="7.75" height="78" x="104.625" y="0" rx="3"
                                            ry="3" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2131"
                                            d="M 104.625 78L 104.625 46.472Q 104.625 43.472 107.625 43.472L 109.375 43.472Q 112.375 43.472 112.375 46.472L 112.375 46.472L 112.375 78L 112.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 104.625 78L 104.625 46.472Q 104.625 43.472 107.625 43.472L 109.375 43.472Q 112.375 43.472 112.375 46.472L 112.375 46.472L 112.375 78L 112.375 78z"
                                            pathFrom="M 104.625 78L 104.625 78L 112.375 78L 112.375 78L 112.375 78L 112.375 78L 112.375 78L 104.625 78"
                                            cy="43.472" cx="135.625" j="3" val="1328" barHeight="34.528"
                                            barWidth="7.75"></path>
                                        <rect id="SvgjsRect2132" width="7.75" height="78" x="135.625" y="0" rx="3"
                                            ry="3" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2134"
                                            d="M 135.625 78L 135.625 50.97Q 135.625 47.97 138.625 47.97L 140.375 47.97Q 143.375 47.97 143.375 50.97L 143.375 50.97L 143.375 78L 143.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 135.625 78L 135.625 50.97Q 135.625 47.97 138.625 47.97L 140.375 47.97Q 143.375 47.97 143.375 50.97L 143.375 50.97L 143.375 78L 143.375 78z"
                                            pathFrom="M 135.625 78L 135.625 78L 143.375 78L 143.375 78L 143.375 78L 143.375 78L 143.375 78L 135.625 78"
                                            cy="47.97" cx="166.625" j="4" val="1155" barHeight="30.03" barWidth="7.75">
                                        </path>
                                        <rect id="SvgjsRect2135" width="7.75" height="78" x="166.625" y="0" rx="3"
                                            ry="3" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2137"
                                            d="M 166.625 78L 166.625 38.568Q 166.625 35.568 169.625 35.568L 171.375 35.568Q 174.375 35.568 174.375 38.568L 174.375 38.568L 174.375 78L 174.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 166.625 78L 166.625 38.568Q 166.625 35.568 169.625 35.568L 171.375 35.568Q 174.375 35.568 174.375 38.568L 174.375 38.568L 174.375 78L 174.375 78z"
                                            pathFrom="M 166.625 78L 166.625 78L 174.375 78L 174.375 78L 174.375 78L 174.375 78L 174.375 78L 166.625 78"
                                            cy="35.568" cx="197.625" j="5" val="1632" barHeight="42.432"
                                            barWidth="7.75"></path>
                                        <rect id="SvgjsRect2138" width="7.75" height="78" x="197.625" y="0" rx="3"
                                            ry="3" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#E5E7EB" class="apexcharts-backgroundBar"></rect>
                                        <path id="SvgjsPath2140"
                                            d="M 197.625 78L 197.625 46.263999999999996Q 197.625 43.263999999999996 200.625 43.263999999999996L 202.375 43.263999999999996Q 205.375 43.263999999999996 205.375 46.263999999999996L 205.375 46.263999999999996L 205.375 78L 205.375 78z"
                                            fill="rgba(26,86,219,1)" fill-opacity="1" stroke-opacity="1"
                                            stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                            class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMasks63ht3cf)"
                                            pathTo="M 197.625 78L 197.625 46.263999999999996Q 197.625 43.263999999999996 200.625 43.263999999999996L 202.375 43.263999999999996Q 205.375 43.263999999999996 205.375 46.263999999999996L 205.375 46.263999999999996L 205.375 78L 205.375 78z"
                                            pathFrom="M 197.625 78L 197.625 78L 205.375 78L 205.375 78L 205.375 78L 205.375 78L 205.375 78L 197.625 78"
                                            cy="43.263999999999996" cx="228.625" j="6" val="1336"
                                            barHeight="34.736000000000004" barWidth="7.75"></path>
                                        <g id="SvgjsG2119" class="apexcharts-bar-goals-markers"
                                            style="pointer-events: none">
                                            <g id="SvgjsG2121" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2124" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2127" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2130" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2133" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2136" className="apexcharts-bar-goals-groups"></g>
                                            <g id="SvgjsG2139" className="apexcharts-bar-goals-groups"></g>
                                        </g>
                                    </g>
                                    <g id="SvgjsG2118" class="apexcharts-datalabels" data:realIndex="0"></g>
                                </g>
                                <line id="SvgjsLine2162" x1="0" y1="0" x2="217" y2="0" stroke="#b6b6b6"
                                    stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                    class="apexcharts-ycrosshairs"></line>
                                <line id="SvgjsLine2163" x1="0" y1="0" x2="217" y2="0" stroke-dasharray="0"
                                    stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                <g id="SvgjsG2164" class="apexcharts-yaxis-annotations"></g>
                                <g id="SvgjsG2165" class="apexcharts-xaxis-annotations"></g>
                                <g id="SvgjsG2166" class="apexcharts-point-annotations"></g>
                            </g>
                            <g id="SvgjsG2150" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g>
                            <g id="SvgjsG2106" class="apexcharts-annotations"></g>
                        </svg>
                        <div class="apexcharts-legend" style="max-height: 70px"></div>
                        <div class="apexcharts-tooltip apexcharts-theme-light">
                            <div class="apexcharts-tooltip-title" style="
                                    font-family: Inter, sans-serif;
                                    font-size: 14px;
                                "></div>
                            <div class="apexcharts-tooltip-series-group" style="order: 1">
                                <span class="apexcharts-tooltip-marker"
                                    style="background-color: rgb(26, 86, 219)"></span>
                                <div class="apexcharts-tooltip-text" style="
                                        font-family: Inter, sans-serif;
                                        font-size: 14px;
                                    ">
                                    <div class="apexcharts-tooltip-y-group">
                                        <span class="apexcharts-tooltip-text-y-label"></span><span
                                            class="apexcharts-tooltip-text-y-value"></span>
                                    </div>
                                    <div class="apexcharts-tooltip-goals-group">
                                        <span class="apexcharts-tooltip-text-goals-label"></span><span
                                            class="apexcharts-tooltip-text-goals-value"></span>
                                    </div>
                                    <div class="apexcharts-tooltip-z-group">
                                        <span class="apexcharts-tooltip-text-z-label"></span><span
                                            class="apexcharts-tooltip-text-z-value"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                            <div class="apexcharts-yaxistooltip-text"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="w-full">
                    <h3 class="mb-2 text-base font-normal text-gray-500 dark:text-gray-400">
                        Audience by age
                    </h3>
                    <div class="flex items-center mb-2">
                        <div class="w-16 text-sm font-medium dark:text-white">
                            50+
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary-600 h-2.5 rounded-full dark:bg-primary-500" style="width: 18%"></div>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-16 text-sm font-medium dark:text-white">
                            40+
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary-600 h-2.5 rounded-full dark:bg-primary-500" style="width: 15%"></div>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-16 text-sm font-medium dark:text-white">
                            30+
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary-600 h-2.5 rounded-full dark:bg-primary-500" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="flex items-center mb-2">
                        <div class="w-16 text-sm font-medium dark:text-white">
                            20+
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-primary-600 h-2.5 rounded-full dark:bg-primary-500" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
                <div id="traffic-channels-chart" class="w-full"></div>
            </div>
        </div>
        <div class="2xl:col-span-2 w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
            <div class="flex justify-between">
                <div>
                    <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">
                        32.4k
                    </h5>
                    <p class="text-base font-normal text-gray-500 dark:text-gray-400">
                        Users this week
                    </p>
                </div>
                <div
                    class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                    12%
                    <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13V1m0 0L1 5m4-4 4 4" />
                    </svg>
                </div>
            </div>
            <div id="area-chart"></div>
            <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                <div class="flex justify-between items-center pt-5">
                    <!-- Button -->
                    <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown"
                        data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
                        type="button">
                        Last 7 days
                        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="lastDaysdropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    7 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    30 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last
                                    90 days</a>
                            </li>
                        </ul>
                    </div>
                    <a href="#"
                        class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                        Users Report
                        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-gray-100 py-10">
            <div class="mx-auto max-w-8xl">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">
                                Today's ExchangeRate
                            </h1>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col">
                        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg relative">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Currency
                                                </th>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Unit
                                                </th>
                                                <th scope="col"
                                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                    Buy
                                                </th>
                                                <th scope="col"
                                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                    Sell
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr v-for="ter in paginatedData" :key="ter.currency.iso3">
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    {{ ter.currency.iso3 }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                    {{ ter.currency.unit }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ ter.buy }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ ter.sell }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination Controls -->
                            <div class="flex justify-between mt-4">
                                <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)"
                                    class="ml-8 px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-700">
                                    Previous
                                </button>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">Page {{ currentPage }} of
                                        {{ totalPages }}</span>
                                </div>
                                <button :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)"
                                    class="mr-8 px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-700">
                                    Next
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer
            class="p-4 my-6 mx-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">
            <ul class="flex flex-wrap items-center mb-6 space-y-1 md:mb-0">
                <li>
                    <a href="#"
                        class="mr-4 text-sm font-normal text-gray-500 hover:underline md:mr-6 dark:text-gray-400">Terms
                        and conditions</a>
                </li>
                <li>
                    <a href="#"
                        class="mr-4 text-sm font-normal text-gray-500 hover:underline md:mr-6 dark:text-gray-400">Privacy
                        Policy</a>
                </li>
                <li>
                    <a href="#"
                        class="mr-4 text-sm font-normal text-gray-500 hover:underline md:mr-6 dark:text-gray-400">Licensing</a>
                </li>
                <li>
                    <a href="#"
                        class="mr-4 text-sm font-normal text-gray-500 hover:underline md:mr-6 dark:text-gray-400">Cookie
                        Policy</a>
                </li>
                <li>
                    <a href="#" class="text-sm font-normal text-gray-500 hover:underline dark:text-gray-400">Contact</a>
                </li>
            </ul>
            <div class="flex space-x-6 sm:justify-center">
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                        </path>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </footer>
        <p class="my-10 text-sm text-center text-gray-500">
            © 2024
            <a href="https://google.com/" class="hover:underline" target="_blank">google.com</a>. All rights reserved.
        </p>
    </AdminLayout>
</template>
