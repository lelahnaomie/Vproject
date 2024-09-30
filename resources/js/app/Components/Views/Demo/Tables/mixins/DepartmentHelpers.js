export const TableHelpers = {
    data() {
        return {
            tableColumns : [
                {
                    title: this.$t('ID'),
                    type: 'text',
                    key: 'id',
            
                },
                {
                    title: this.$t('name'),
                    type: 'text',
                    key: 'name',
            
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