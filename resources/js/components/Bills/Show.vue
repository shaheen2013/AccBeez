<template>
    <div >
        <h2>Hello</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script >

export default {
    name: 'BillView',
    data() {
        return {
            routeName: '',
            operation: 'view',
        };
    },
    async created() {
    },
    methods: {

    },
};
</script>


<style scoped>
    .demo-bill {
        padding-left: 10px;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    th {
        padding-left: 0 !important;
        padding-top: 0 !important;
    }
    .required-indicator {
        color: red;
        margin-right: 3px;
    }
</style>>
