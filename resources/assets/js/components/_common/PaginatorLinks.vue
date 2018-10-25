<style lang="scss" scoped>
    li.page-item.active {
        & > a.page-link {
            background-color: #3F729B;
            border-color: #3F729B;
            font-weight: bold;
        }
        &.disabled > a.page-link {
            color: white;
        }
    }
</style>

<template>
    <nav>
        <ul class="pagination justify-content-end m-0">
            <!-- Primera -->
            <li :class="`page-item ${paginator.current_page <= 1 ? 'disabled' : ''}`">
                <a class="page-link" :disabled="paginator.current_page <= 1" @click.prevent="changePage(1)">Primera</a>
            </li>
            <!-- Previous -->
            <li :class="`page-item ${paginator.current_page <= 1 ? 'disabled' : ''}`">
                <a class="page-link" :disabled="paginator.current_page <= 1" @click.prevent="changePage(paginator.current_page - 1)">
                    <span>&laquo;</span>
                </a>
            </li>
            <!-- Dots -->
            <li class="page-item disabled" v-if="paginator.current_page - Math.floor(offset / 2) > 1">
                <a class="page-link">
                    ...
                </a>
            </li>
            <!-- Links -->
            <li v-for="(page, key) in pages" :key="key" :class="`page-item ${paginator.current_page === page ? 'disabled' : ''} ${paginator.current_page === page ? 'active' : ''}`">
                <a class="page-link" :disabled="paginator.current_page === page" @click.prevent="changePage(page)">
                    {{ page }}
                </a>
            </li>
            <!-- Dots -->
            <li class="page-item disabled" v-if="paginator.current_page + Math.floor(offset / 2) < paginator.last_page">
                <a class="page-link">
                    ...
                </a>
            </li>
            <!-- Next -->
            <li :class="`page-item ${paginator.current_page >= paginator.last_page ? 'disabled' : ''}`">
                <a class="page-link" :disabled="paginator.current_page >= paginator.last_page" @click.prevent="changePage(paginator.current_page + 1)">
                    <span>&raquo;</span>
                </a>
            </li>
            <!-- Last -->
            <li :class="`page-item ${paginator.current_page >= paginator.last_page ? 'disabled' : ''}`">
                <a class="page-link" :disabled="paginator.current_page >= paginator.last_page" @click.prevent="changePage(paginator.last_page)">Ultima</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: {
            paginator: {
                type: Object,
                required: true,
            },
        },
        data() {
            return {
                offset: 5
            };
        },
        methods: {
            isCurrentPage(page) {
                return this.paginator.current_page === page;
            },
            changePage(page) {
                if (page > this.paginator.last_page) {
                    page = this.paginator.last_page;
                }
                this.$emit('paginate', page);
            }
        },
        computed: {
            pages() {
                let pages = [];
                let from = this.paginator.current_page - Math.floor(this.offset / 2);
                if (from < 1) {
                    from = 1;
                }
                let to = from + this.offset - 1;
                if (to > this.paginator.last_page) {
                    to = this.paginator.last_page;
                }
                while (from <= to) {
                    pages.push(from);
                    from++;
                }
                return pages;
            }
        }
    }
</script>