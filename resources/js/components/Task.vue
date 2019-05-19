<template>
    <tr :id="task.id"
        :class="{ 'table-info': task.status == 1, 'table-danger': task.status == 2, 'table-success': task.status == 3 }">
        <td class="col-1">{{ task.startDate }}</td>
        <td class="col-1">{{ task.endDate }}</td>
        <td class="col-1">{{ task.username }}</td>
        <td class="col-1">{{ task.task }}</td>
        <td class="col"><span v-if="task.status == 1"><i class="fas fa-spinner fa-spin"></i></span> {{ task.message }}</td>
    </tr>
</template>

<script>
    export default {
        props: ['task']
    }
</script>