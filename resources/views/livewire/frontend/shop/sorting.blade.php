<nav class="toolbox sticky-toolbox sticky-content fix-top">
  <div class="toolbox-left">
    <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle
                                          btn-icon-left d-block d-lg-none"><i
        class="w-icon-category"></i><span>Filters</span></a>
    <div class="toolbox-item toolbox-sort select-box text-dark">
      <label>Sort By :</label>
      <select name="orderby" class="form-control" wire:model="sortOrder">
        <option value="">Select...</option>
        <option value="top_selling">Best Selling</option>
        <option value="date">Sort by latest</option>
        <option value="price_desc" >High - Low Price</option>
        <option value="price_asc">Low - High Price</option>
        <option value="offer">Offer Product</option>
      </select>
    </div>
  </div>
  <div class="toolbox-right">
    <div class="toolbox-item toolbox-show select-box" >
      <select name="count" class="form-control" wire:model="showPerPage">
        <option value="">Show Per Page</option>
        <option value="30">Show 30</option>
        <option value="50">Show 50</option>
        <option value="100">Show 100</option>
      </select>
    </div>
  </div>
</nav>

