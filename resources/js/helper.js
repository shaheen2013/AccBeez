function getLogedInUser(){
    axios.get(`/logged_in_user`).
    then((res) => {
        console.log('logged_in_user:', this.logged_in_user);
        return res.data;
    });
};

export default getLogedInUser;