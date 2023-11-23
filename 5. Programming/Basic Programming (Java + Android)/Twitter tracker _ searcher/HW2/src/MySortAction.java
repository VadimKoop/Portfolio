

import interfaces.SortAction;

public class MySortAction implements SortAction {

	int _field;
	int _order;
	
	@Override
	public void setSortField(int field) {
		_field = field;
	}

	@Override
	public int getSortField() {
		return _field;
	}

	@Override
	public void setSortOrder(int order) {
		_order = order;
	}

	@Override
	public int getSortOrder() {
		return _order;
	}

}
