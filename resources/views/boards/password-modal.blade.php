<div id="passwordModal" class="hidden">
    <form method="POST" id="passwordForm">
        @csrf
        <div class="mb-4">
            <label>비밀번호</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">확인</button>
    </form>
</div>

<script>
function checkPassword(action, id) {
    const modal = document.getElementById('passwordModal');
    const form = document.getElementById('passwordForm');
    
    modal.classList.remove('hidden');
    form.action = action;  // 수정 또는 삭제 URL 설정
}
</script> 