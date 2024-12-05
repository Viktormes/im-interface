import axios from 'axios';
import dayjs from "dayjs";
import isoWeek from 'dayjs/plugin/isoWeek';
import 'dayjs/locale/sv';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

dayjs.extend(isoWeek);
dayjs.locale('sv');
