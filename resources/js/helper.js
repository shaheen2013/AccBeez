function getLogedInUser(){
    axios.get(`/logged_in_user`).
    then((res) => {
        return res.data;
    });
};

function formatNumberToFraction(value) {
    if (value !== null && value !== undefined) {
      return value.toLocaleString('en-US', {minimumFractionDigits: 4, maximumFractionDigits: 4});
    }
}

const commonRoutes = [
    'companies',
    'users'
];

export default {getLogedInUser, formatNumberToFraction, commonRoutes};