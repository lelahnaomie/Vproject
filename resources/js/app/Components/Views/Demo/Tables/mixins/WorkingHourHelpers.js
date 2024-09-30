export const TableHelpers = {
    data() {
        return {
            tableColumns : [
                {
                    title: this.$t('name'),
                    type: 'text',
                    key: 'full_time',
            
                },
                {
                    title: this.$t('Start Time'),
                    type: 'text',
                    key: 'start',
            
                },
                {
                    title: this.$t('End Time'),
                    type: 'text',
                    key: 'end',
            
                },
                // {
                //     title: this.$t('age'),
                //     type: 'text',
                //     key: 'age',
                // },
            //     {
            //         title: this.$t('gender'),
            //         type: 'custom-html',
            //         key: 'gender',
            //         modifier: (value) => {
            //             return `<span>${this.$t(value)}</span>`
            
            //         }
            //     },
            ],
            actionObj : {
                title: this.$t('action'),
                type: 'action',
            }
        }
    },
}