<template>
    <tr :id="task.id"
        :class="{ 'table-info': task.status == 1, 'table-danger': task.status == 2, 'table-success': task.status == 3 }">
        <td>{{ task.startDate }}</td>
        <td>{{ task.endDate }}</td>
        <td>{{ task.username }}</td>
        <td>{{ task.task }}</td>
        <td><span v-if="task.status == 1"><i class="fas fa-spinner fa-spin"></i></span> {{ task.message }}</td>
    </tr>
</template>

<script>
    export default {
        props: ['task']
    }
</script>