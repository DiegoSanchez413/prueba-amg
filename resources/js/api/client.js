import axios from 'axios';

const listClients = async () => {
    const response = await axios.get('/api/listClients');
    // this.items = response.data;
    return response.data;

}


export  { listClients }
