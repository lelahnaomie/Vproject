import AppFunction from "../../../../../../core/helpers/app/AppFunction"
export const TableHelpers = {
    data() {
        return {
            tableColumns : [
                {
                    title: this.$t('employee'),
                    type: 'employee-media-object',
                    key: 'media-object',
                    mediaTitleKey: 'full_name',
                    mediaSubtitleKey: 'department',
                    default: AppFunction.getAppUrl('images/avatar-demo.jpg'),
                    modifier: (value, row) => {
                        return value;
                    },
                    isVisible: true
                },
                {
                    title: this.$t('email'),
                    type: 'text',
                    key: 'email',
            
                },
                {
                    title: this.$t('Attendance'),
                    type: 'text',
                    key: 'attendance_id',
            
                },
                {
                    title: this.$t('Department'),
                    type: 'object',
                    key: 'department',
                    modifier: (value, row) => {
                        if(value.name){
                            return value.name;
                        }
                        return "Unknown";
                    }    
                },
                {
                    title: this.$t('Working Hours'),
                    type: 'array-object',
                    key: 'workinghour',
                    modifier: (value, row) => {
                        if(value.length != 0){
                            let data = value[0];
                            return data.full_time;
                        }
                        return "Unknown";
                    }    
            
                },
                {
                    title: this.$t('Tel Number'),
                    type: 'text',
                    key: 'tel_number',
                },
            ],
            actionObj : {
                title: this.$t('action'),
                type: 'action',
            }
        }
    },
}