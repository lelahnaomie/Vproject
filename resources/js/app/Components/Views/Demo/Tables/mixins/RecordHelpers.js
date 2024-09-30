export const TableHelpers = {
    data() {
        return {
            tableColumns : [
                {
                    title: this.$t('User ID'),
                    type: 'text',
                    key: 'user_id',
            
                },
                {
                    title: this.$t('Employee'),
                    type:'object',
                    key: 'user',
                    modifier: (value, row) => {
                        if(value.full_name){
                            return value.full_name;
                        }
                        return "Unknown";
                    }    
            
                },
                {
                    title: this.$t('Role'),
                    type: 'text',
                    key: 'role',
                },
                {
                    title: this.$t('Password'),
                    type: 'text',
                    key: 'password',
                },
            ],
            actionObj : {
                title: this.$t('action'),
                type: 'action',
            }
        }
    },
}