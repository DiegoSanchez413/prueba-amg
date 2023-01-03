const formatCurrency = (value) => {
    return "$ " + (value / 100).toFixed(2);
}

const formatDate = (date) => {
    const d = new Date(date)
    const day = d.getDate()
    const monthIndex = d.getMonth()
    const year = d.getFullYear()
    return `${day}/${monthIndex}/${year}`
}

export { formatCurrency, formatDate }