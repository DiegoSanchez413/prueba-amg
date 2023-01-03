import axios from 'axios';

const listClients = async () => {
    const response = await axios.get('/listClients');
    // this.items = response.data;
    return response.data;

}


export  { listClients }
